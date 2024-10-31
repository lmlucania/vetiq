<?php

declare(strict_types=1);

use App\Http\Controllers\Hospital\AuthController;
use App\Http\Controllers\Hospital\HospitalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(static function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:staffs')->group(static function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::controller(HospitalController::class)->group(static function () {
        Route::get('me', 'show')->name('me');
    });
});
