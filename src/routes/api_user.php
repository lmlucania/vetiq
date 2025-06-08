<?php

declare(strict_types=1);

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\FavoriteController;
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
    Route::controller(FavoriteController::class)->prefix('favorites')->name('favorites.')->group(static function () {
        Route::post('{uuid}', 'attach')->name('attach');
        Route::delete('{uuid}', 'detach')->name('detach');
    });
    Route::controller(ReviewController::class)->prefix('hospital')->name('hospital.reviews.')->group(static function () {
        Route::get('{hospitalUuid}/reviews', 'index')->name('index');
        Route::get('{hospitalUuid}/reviews/{uuid}', 'show')->name('show');
        Route::post('{hospitalUuid}/reviews', 'store')->name('store');
        Route::put('{hospitalUuid}/reviews/{uuid}', 'update')->name('update');
    });
    Route::get('reviews', [ReviewController::class, 'indexOwn'])->name('reviews');
});
