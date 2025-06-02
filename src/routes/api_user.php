<?php

declare(strict_types=1);

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PetController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(static function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:users')->group(static function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('pets', PetController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});
