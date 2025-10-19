<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Str;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {          

       try{

            $request->validated([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:15|unique:users,phone',
                'password' => 'required|string|min:8|confirmed',
                'gender' => 'required',
                // 'profile' => 'nullable|file|mimes:jpg,jpeg,png|max:20480'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
                'role' => $request->get("role", 'user'),
                'primary_sport_preference' => $request->get("preference"),
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zipcode' => $request->zipCode,
            ]);

            // if($request->hasFile('profile')){
            //     $imagePath = $request->file('profile')->store('profile_images', 'public');
            //     $user->profile = $imagePath;
            //     $user->save();
            // }

            if($user){
                 $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    "message" =>'User registered successfully', 
                    "user" => $user, 
                    "statusCode" => 201, 
                    "token" => $token
                ]);
            }
       }catch(Exception $e){
            return ResponseHelper::error(message: 'Unable to register!, please try again, '.$e->getMessage(), statusCode: 400);
       }
    }

    public function login(LoginRequest $request){
       
        try{
            // dd(Auth::attempt($request->only('email', 'password')));
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                Log::error('Login error: Invalid credentials for email: '.$request->email);
                return ResponseHelper::error(message: 'Invalid credentials', statusCode: Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return ResponseHelper::success(
                message: 'Logged in successfully.', 
                data: [
                    'token' => $token,
                    'user' => $user
                ], 
                statusCode: 200
            );

        }
        catch(Exception $e){
            Log::error('Login error: '.$e->getMessage());
            return ResponseHelper::error(message: 'Unable to login!, please try again, '.$e->getMessage(), statusCode: 500);
        }
       
    }

    public function requestOtp(Request $request){
        $request->validate([
            "phone" => "required|string"
        ]);

       
        $phone  = $request->phone;

        $user = User::where('phone', $phone)->first();

        if(!$user){
            return response()->json(['message' => 'User is not available, Please register first.'], 404);
        }

        $recent = Otp::where('phone', $phone)->latest()->first();
        if($recent && $recent->created_at->diffInSeconds(now()) < 60 ){
            return response()->json([
                'message' => 'Please wait before requesting a new OTP.'
            ], 429);
        }

        // Generate OTP
        
        $otpPlain = random_int(100000, 999999);
        $otpHash = Hash::make((string)$otpPlain);

        Otp::create([
            'phone' => $phone,
            'otp_hash' => $otpHash,
            'expires_at' => now()->addMinutes(5)
        ]);

        $notifiable = (object)['mail' => $phone];

        // Notification::send($notifiable, new SendOtpNotification($otpPlain));
        Notification::route('mail', $phone)->notify(new SendOtpNotification($otpPlain));

        return response()->json([
            'message' => 'OTP Sent!',
            'otp' => $otpPlain
        ]);
    }


    public function verifyOtp(Request $request){
        $phone = $request->phone;
        $otp = $request->otp;
        $request->validate([
            "phone" => "required",
            "otp" => "required|numeric|min:6"
        ]);

        $otpRecord  = Otp::where('phone', '=',$phone)->latest()->first();
        // $query = vsprintf(str_replace(array('?'), array('\'%s\''), $otpRecord->toSql()), $otpRecord->getBindings());
        if(!$otpRecord){
            return response()->json(['message' => 'OTP not found, please request a new.'], 404);
        }

        // check expiry
        if(Carbon::now()->greaterThan($otpRecord->expires_at)){
            return response()->json(['message' => 'OTP expired, request a new one.'], 410);
        }

        // check attempts
        if($otpRecord->attempts >= 5){
            return response()->json(['message' => 'OTP expired, request a new one.'],429);
        }

        // verify hashed OTP
        if(!Hash::check($otp, $otpRecord->otp_hash)){
            $otpRecord->increment('attempts');
            return response()->json(['message' => 'Invalid OTP.'], 401);
        }

        $otpRecord->delete();


        $user = User::firstOrCreate(['phone' => $phone], [
            'name' =>  'User '. substr($phone, -4),
            'phone' => $request->phone,
            'email' => $request->phone,
            'password' => bcrypt(\Str::random(16))
        ]);

        // $user = User::where('phone', $phone)->first();

        // if(!$user){
        //     return response()->json(['message' => 'User is not register with system.'], 404);
        // }

        //create token

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Authenticated',
            'token' => $token,
            'user' => $user
        ]);
    }


    // Logout
    public function logout(Request $request)
    {
        try{

            $user =  Auth::user();

            if(!$user){  
                return ResponseHelper::error(message: "Enable to logout due to invalid token", statusCode: response::HTTP_UNAUTHORIZED);
            }

            $user->currentAccessToken()->delete();
            return  ResponseHelper::success(message:'Logged out successfully', statusCode:Response::HTTP_OK); 

        }catch(Exception $e){
            return ResponseHelper::error(message: "Enable to logout due to exceptional ".$e->getMessage(), statusCode: response::HTTP_UNAUTHORIZED);
        }
    }

    // Get Authenticated User
    public function user(Request $request)
    {

        $user = Auth::user();

        if ($user) {

            if ($user) {
                return ResponseHelper::success(
                    statusCode: Response::HTTP_OK, message: 'user profile.', data: [
                        'user' => $user
                    ]);
            }

        }

        return ResponseHelper::error(
            statusCode: Response::HTTP_UNAUTHORIZED, message: 'Unauthenticated user'
        );
    }

    public function updateUser(Request $request)
    {
       try {
        
            $isLoggedIn = Auth::user();

            if (!$isLoggedIn) {
                return ResponseHelper::error(
                    statusCode:Response::HTTP_UNAUTHORIZED,
                    message: 'Invalid or missing token',
                );
            }

            $user = User::find($isLoggedIn->id);
            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('profile_images', $filename, 'public');
                $user->profile = $filename; 
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->zipcode = $request->zipcode;
            $user->short_desc = $request->short_desc;
            $user->dob = $request->date_of_birth;
            $user->primary_sport_preference = $request->primary_sport_preference;
            $user->save();

            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'User updated successfully',
                data: $user
            );

        } catch(Exception $e){
            return ResponseHelper::error(
                statusCode: Response::HTTP_UNAUTHORIZED,
                message: 'Unable to update user, '.$e->getMessage(),
            );
        }
    }

}