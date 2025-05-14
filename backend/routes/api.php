<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TurfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('logout', 'logout');
        Route::get("user",  'user');
        Route::post("user-update",  'updateUser');
    });
});


Route::controller(TeamController::class)->group(function(){
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('teams', 'index');
        Route::get('team/edit/{id}', 'show');
        Route::post('team/create', 'store');
        Route::post('team/update/{id}', 'update');
    });
});

/*
Route::post('/register', [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login'])->name('login');
Route::post("/sent-otp", [AuthController::class,'sentOtp'])->name('login.otp');
Route::post("/verify-top", [AuthController::class, 'verifyOtp'])->name('login.verify');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('turfs', [TurfController::class, 'availableTurfs']);

Route::get("/user", [AuthController::class, 'user']);
Route::post("/user/update", [AuthController::class, 'updateUser']);

Route::middleware('auth:api')->group(function () {
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

*/

