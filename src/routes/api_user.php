<?php

declare(strict_types=1);

use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\HospitalController;
use App\Http\Controllers\User\HospitalViewHistoryController;
use App\Http\Controllers\User\PetController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(static function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:users')->group(static function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('pets', PetController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::controller(UserController::class)->prefix('profile')->name('profile.')->group(static function () {
        Route::get('', 'me')->name('me');
        Route::post('', 'update')->name('update');
        Route::delete('', 'destroy')->name('destroy');
    });
    Route::resource('favorites', FavoriteController::class)->only(['index']);
    Route::controller(FavoriteController::class)->prefix('hospital/{hospital}/favorites')->name('hospital.favorites.')->group(static function () {
        Route::post('', 'attach')->name('attach');
        Route::delete('', 'detach')->name('detach');
    });
    Route::resource('hospital.reviews', ReviewController::class)->only(['index', 'store', 'show', 'update']);
    Route::get('reviews', [ReviewController::class, 'indexOwn'])->name('reviews.index');
    Route::resource('hospitals', HospitalController::class)->only(['index', 'show']);
    Route::resource('appointments', AppointmentController::class)->only(['index', 'store', 'show']);
    Route::controller(AppointmentController::class)->prefix('hospital')->name('appointments.')->group(static function () {
        Route::patch('appointments/{id}/cancel', 'cancel')->name('cancel');
    });
    Route::controller(HospitalViewHistoryController::class)->prefix('hospital/{hospital}/view-histories')->name('hospital.view-histories.')->group(static function () {
//        Route::get('', 'index')->name('index');
        Route::delete('', 'destroy')->name('delete');
    });
});
