<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:user,manager,admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
        ]);
    }

    public function login(Request $request){
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $user = User::where('email', $request->email)->first();
     
        if(!$user && !Hash::check($request->password, $user?->password)){
            return response()->json([
                "error" => ['The provided credential are incorrect']
            ]);
        }

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
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
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Get Authenticated User
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
