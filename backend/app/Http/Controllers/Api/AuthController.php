<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
           
       try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if($request->hasFile('profile')){
                $imagePath = $request->file('profile')->store('profile_images', 'public');
                $user->profile = $imagePath;
                $user->save();
            }

            if($user){
                return ResponseHelper::success(message: 'User registered successfully', data: $user, statusCode: 201);
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

    public function sentOtp(Request $request){
        $request->validate([
            "phone" => "required"
        ]);

        $otp = rand(100000, 999999);
        Cache::put('otp_'. $request->phone, $otp, now()->addMinutes((5)));

        // Example: Send OTP via SMS (use Twilio, Firebase, or an SMS API)
        // Http::post('https://smsprovider.com/send', ['phone' => $request->phone, 'otp' => $otp]);


        return response()->json([
            'message' => 'OTP Sent!',
            'otp' => $otp
        ]);
    }


    public function verifyOtp(Request $request){
        $phone = $request->phone;
        $request->validate([
            "phone" => "required",
            "otp" => "required|numeric|min:6|max:6"
        ]);

        if(Cache::get("otp_".$phone) != $request->otp){
            return response()->json(['error' => 'Invalid OTP.'], 401);
        }

        Cache::forget("otp_".$phone);

        $user = User::firstOrCreate(['phone' => $phone]);

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
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

        Log::info('All request data:', $request->all());
        Log::info('Has file profile: ' . ($request->hasFile('profile') ? 'yes' : 'no'));


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
            $user->gender = $request->gender;
            $user->short_desc = $request->short_desc;
            $user->save();

            return ResponseHelper::success(
                statusCode: Response::HTTP_OK,
                message: 'User updated successfully',
                data: []
            );

        } catch(Exception $e){
            return ResponseHelper::error(
                statusCode: Response::HTTP_UNAUTHORIZED,
                message: 'Unable to update user, '.$e->getMessage(),
            );
        }
    }

}