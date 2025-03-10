<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::options('{any}', function (Request $request) {
    return response()->json('Preflight OK', 200, [
        'Access-Control-Allow-Origin' => 'http://localhost:1234/',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        'Access-Control-Allow-Credentials' => 'true',
    ]);
})->where('any', '.*');

Route::post('/register', [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login'])->name('login');
Route::post("/sent-otp", [AuthController::class,'sentOtp'])->name('login.otp');
Route::post("/verify-top", [AuthController::class, 'verifyOtp'])->name('login.verify');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);


    // Routes for Admins only
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin-dashboard', function () {
            return response()->json(['message' => 'Welcome, Admin!']);
        });
    });

    // Routes for Managers and Admins
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/manager-dashboard', function () {
            return response()->json(['message' => 'Welcome, Manager!']);
        });
    });

    // Routes for Normal Users
    Route::middleware('role:user,manager,admin')->group(function () {
        Route::get('/user-dashboard', function () {
            return response()->json(['message' => 'Welcome, User!']);
        });
    });
});

