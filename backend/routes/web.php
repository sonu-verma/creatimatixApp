<?php

use App\Http\Controllers\Admin\FileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\TurfController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if(!auth()->check())
    {
        return redirect('login');
    }
});

// Auth routes (Laravel UI)
Auth::routes();




Route::group([
    'middleware' => ['auth'],   // Add multiple middleware as an array if needed
    'prefix' => 'admin'         // URL will start with /admin
], function () {
    // Turf Routes Start
    Route::get('turfs', [TurfController::class, 'index'])->name('turfs');
    Route::get('turf/create', [TurfController::class, 'create'])->name('turf.create');
    Route::get('turf/edit/{id}', [TurfController::class, 'edit'])->name('turf.edit');

    Route::post('/turf/basic-store', [TurfController::class, 'storeBasic'])->name('turf.store.basic');
    Route::post('/turf/images-store', [TurfController::class, 'storeImages'])->name('turf.store.images');
    Route::post('/turf/image/delete/{id}', [TurfController::class, 'deleteImage'])->name('turf.image.remove');

    // Sports Routes
    Route::post('/turf/sports-store', [SportController::class, 'storeSports'])->name('turf.store.sport');
    Route::get('/turf/sports-edit/{sport}', [SportController::class, 'editSports'])->name('turf.edit.sport');
    Route::post('/turf/sports-update', [SportController::class, 'updateSports'])->name('turf.update.sport');
    Route::post('/turf/sports-delete/{sport}', [SportController::class, 'deleteSports'])->name('turf.delete.sport');


   // Turf Routes Start
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware('auth')->name('dashboard');

    // Profile
    Route::get('/profile', [HomeController::class, 'profile'])->middleware('auth')->name('profile');

    // Settings
    Route::get('/settings', [HomeController::class, 'settings'])->middleware('auth')->name('settings');

    // Upload Turf Image
    Route::post('/file/create/{type}', [FileController::class, 'actionCreate'])->name('file.create');
        
});