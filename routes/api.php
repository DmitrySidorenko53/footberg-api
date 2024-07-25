<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TwoFAController;
use App\Http\Controllers\Api\V1\PasswordController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => 'v1'
    ],
    function () {

        Route::group(
            [
                'prefix' => 'account'
            ],
            function () {
                Route::post('/register', [AccountController::class, 'register']);
                Route::post('/login', [AccountController::class, 'login']);
                Route::post('/logout', [AccountController::class, 'logout'])->middleware('token');
            }
        );

        Route::group(
            [
                'prefix' => 'code'
            ],
            function () {
                Route::post('/confirm', [AccountController::class, 'confirmAccount']);
                Route::post('/refresh', [AccountController::class, 'refreshConfirmationCode']);
            }
        );

        Route::group(
            [
                'prefix' => 'token'
            ],
            function () {
                Route::post('/token/refresh', [AccountController::class, 'refreshToken']);
            }
        );

        Route::group(
            [
                'prefix' => 'two-factor',
                'middleware' => ['token']
            ],
            function () {
                Route::post('/login-with-sms', [TwoFAController::class, 'loginIf2FAEnabled'])->withoutMiddleware('token');
                Route::post('/add-phone-number', [TwoFAController::class, 'addPhoneNumber']);
                Route::post('/enable', [TwoFAController::class, 'enableTwoFactorAuthentication']);
                Route::post('/disable', [TwoFAController::class, 'disableTwoFactorAuthentication']);
            }
        );

        Route::group(
            [
                'prefix' => 'password'
            ],
            function () {
                Route::post('/change', [PasswordController::class, 'changePassword'])->middleware('token');
                Route::post('/forgot', [PasswordController::class, 'forgotPassword']);
                Route::post('/reset', [PasswordController::class, 'resetPassword']);
                Route::post('/recovery', [PasswordController::class, 'recoveryPassword']);
            }
        );

        Route::group(
            [
                'prefix' => 'profile',
                'middleware' => ['token']
            ],
            function () {
                Route::post('/fill', [ProfileController::class, 'fill']);
                Route::put('/locale/{lang}', [ProfileController::class, 'changeLanguage']);
                Route::get('/show/{id?}', [ProfileController::class, 'show']);
            }
        );
    }
);

