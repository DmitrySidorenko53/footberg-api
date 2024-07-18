<?php

use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\Security\SecurityController;
use App\Http\Controllers\Api\V1\Security\SecurityPasswordController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => 'v1'
    ],
    function () {

        Route::group(
            [
                'prefix' => 'security'
            ],
            function () {
                Route::post('/register', [SecurityController::class, 'register']);
                Route::post('/login', [SecurityController::class, 'login']);

                Route::post('/code/confirm', [SecurityController::class, 'confirm']);
                Route::post('/code/refresh', [SecurityController::class, 'refreshConfirmationCode']);

                Route::post('/token/refresh', [SecurityController::class, 'refreshToken']);

                Route::group(
                    [
                        'prefix' => 'password'
                    ],
                    function () {
                        Route::post('/change', [SecurityPasswordController::class, 'changePassword'])->middleware('token');
                        Route::post('/forgot', [SecurityPasswordController::class, 'forgotPassword']);
                        Route::post('/reset', [SecurityPasswordController::class, 'resetPassword']);
                        Route::post('/recovery', [SecurityPasswordController::class, 'recoveryPassword']);
                    });
            }
        );

        Route::group(
            [
                'prefix' => 'profile',
                'middleware' => ['token']
            ],
            function () {
                Route::post('/fill', [ProfileController::class, 'fill']);
                Route::post('/logout', [ProfileController::class, 'logout']);

                Route::put('/language/change/{lang}', [ProfileController::class, 'changeLanguage']);

                Route::get('/show/{id?}', [ProfileController::class, 'show']);
            }
        );
    }
);

