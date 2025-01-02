<?php

declare(strict_types=1);

use App\Http\Controllers\Hospital\AuthController;
use App\Http\Controllers\Hospital\HospitalController;
use App\Http\Controllers\Hospital\MenuController;
use App\Http\Controllers\Hospital\VetController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(static function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:staffs')->group(static function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::controller(HospitalController::class)->prefix('info')->name('info.')->group(static function () {
        Route::get('/', 'show')->name('show');
        Route::put('/', 'update')->name('update');
    });
    Route::resource('menus', MenuController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::controller(MenuController::class)->prefix('menus')->name('menus.')->group(static function () {
        Route::post('{menu}/publish', 'publish')->name('publish');
        Route::post('{menu}/unpublish', 'unpublish')->name('unpublish');
    });
    Route::resource('vets', VetController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});
