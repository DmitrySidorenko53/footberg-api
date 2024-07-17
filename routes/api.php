<?php

use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SecurityController;
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

                Route::post('/password/forgot', [SecurityController::class, 'forgotPassword']);
                Route::post('/password/reset', [SecurityController::class, 'resetPassword']);
                Route::post('/password/recovery', [SecurityController::class, 'recoveryPassword']);
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

                Route::post('/password/change', [ProfileController::class, 'changePassword']);

                Route::put('/language/change/{lang}', [ProfileController::class, 'changeLanguage']);

                Route::get('/show/{id?}', [ProfileController::class, 'show']);
            }
        );
    });

