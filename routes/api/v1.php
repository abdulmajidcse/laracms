<?php

use App\Http\Controllers\Api\V1\ContentController;
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

                Route::controller(ContentController::class)
                    ->prefix('contents')
                    ->name('contents.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'store')->name('store');
                        Route::get('{id}', 'show')->name('show');
                        Route::put('{id}', 'update')->name('update');
                        Route::delete('{id}', 'destroy')->name('destroy');
                        Route::post('upload-file', 'uploadFile')->name('uploadFile');
                    });
            });
    });
