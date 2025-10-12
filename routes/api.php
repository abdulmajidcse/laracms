<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::prefix('v1')
    ->name('v1.')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::get('user', [AuthController::class, 'user'])->name('user');
                Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            });
    });
