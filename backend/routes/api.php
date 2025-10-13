<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TeamConnectionController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TurfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::post('auth/request-otp',  'requestOtp');
    Route::post('auth/verify-otp',  'verifyOtp');


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
        Route::post('connections', 'userTeamConnection');
    });

});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/teams/{team}/request-connection', [TeamConnectionController::class, 'sendRequest']);
    Route::post('/connections/{id}/accept', [TeamConnectionController::class, 'accept']);
    Route::post('/connections/{id}/reject', [TeamConnectionController::class, 'reject']);
    Route::post('/my-connections', [TeamConnectionController::class, 'myConnections']);
    Route::post('/my-requests', [TeamConnectionController::class, 'myRequests']);
    Route::post('/posts/{postId}/like', [LikeController::class, 'likeToggle']);
});



Route::controller(TurfController::class)->group(function(){
    // Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('turfs', 'availableTurfs');
        Route::get('turf/{slug}',  'getTurf');
    // });
});



Route::controller(PostController::class)->group(function(){
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('posts', 'index');
        Route::get('post/{id}', 'show');
        Route::post('post/create', 'store');
        Route::get('post/edit/{id}', 'edit');
        Route::post('post/update/{id}', 'update');
    });
});


Route::controller(CheckoutController::class)->group(function(){
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('checkout', 'bookTurf');
    });
});




