<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TeamConnectionController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TurfController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SlotController;
use App\Http\Controllers\Api\SlotBookingController;
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
    Route::get('turfs', 'availableTurfs');
    Route::get('turf/{slug}',  'getTurf');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('turf/store',  'storeTurf');
        Route::get('turfs/nearby',  'getNearByTurf');
    });
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




// Review System Routes
Route::controller(ReviewController::class)->group(function(){
    // Public routes
    Route::get('reviews/turf/{turfId}/', 'getTurfReviews');
    Route::get('turfs/{turfId}/reviews/stats', 'getTurfReviewStats');
    
    // Authenticated user routes
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('reviews', 'store');
        Route::get('reviews/my', 'getMyReviews');
        Route::put('reviews/{reviewId}', 'update');
        Route::delete('reviews/{reviewId}', 'destroy');
        Route::get('users/{userId}/reviews', 'getUserReviews');
    });
    
    // Admin routes (you can add admin middleware if needed)
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('reviews/pending', 'getPendingReviews');
        Route::get('turfs/{turfId}/reviews/all', 'getAllTurfReviews');
        Route::put('reviews/{reviewId}/status', 'toggleReviewStatus');
    });
});



Route::get('events', action: [EventController::class, 'index']);

// Public event status routes
Route::controller(EventController::class)->group(function(){
     // Public event status routes
     Route::get('events/status/{status}', 'getEventsByStatus');
     Route::get('events/upcoming', 'getUpcomingEvents');
     Route::get('events/today', 'getTodayEvents');
     Route::get('events/statistics', 'getEventStatistics');
     Route::get('events/sports/{sportsType}', 'getEventsBySportsType');
     Route::post('events/date-range', 'getEventsByDateRange');
});

// Authenticated event management routes
Route::controller(EventController::class)->middleware('auth:sanctum')->group(function(){
     // Generic event routes (must come after specific routes)
     Route::get('events/{id}', [EventController::class, 'show']);
     Route::post("events", [EventController::class, 'store']);
     Route::post('events/{id}', [EventController::class, 'update']);
     Route::delete('events/{id}', [EventController::class, 'destroy']);
});


// Slot Booking Routes
Route::controller(SlotController::class)->group(function(){
    // Public routes
    Route::get('slots/turf/{turfId}', 'getSlots');
    Route::get('slots/turf/{turfId}/react', 'getSlotsForReact');
    
    // Authenticated routes
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('slots/book', 'bookSlots');
        Route::post('slots/cancel/{bookingId}', 'cancelBooking');
        Route::get('slots/my-bookings/{userId}', 'getMyBookings');
        Route::post('slots/block', 'blockSlots');
    });
});

// Slot Booking Routes (New System)
Route::controller(SlotBookingController::class)->group(function(){
    // Authenticated user routes
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('slot-bookings', 'store');
        Route::get('slot-bookings/my', 'getMyBookings');
        Route::get('slot-bookings/my/stats', 'getMyBookingStats');
        Route::get('slot-bookings/{id}', 'show');
    });
    
    // Owner/Admin routes for managing bookings
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('slot-bookings/turf/{turf_id}', 'getTurfBookings');
        Route::put('slot-bookings/{id}/status', 'updateStatus');
    });
});


