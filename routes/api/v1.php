<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\LogoutController;
use App\Http\Controllers\Api\V1\ProfileController;

Route::prefix('v1')
    ->name('v1.')
    ->group(function () {
        Route::post('login', LoginController::class)->name('login');

        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::controller(ProfileController::class)
                    ->prefix('user')
                    ->name('user.')
                    ->group(function () {
                        Route::get('/', 'show')->name('show');
                        Route::patch('/', 'update')->name('update');
                    });

                Route::post('logout', LogoutController::class)->name('logout');
            });
    });
