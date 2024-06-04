<?php

use App\Http\Controllers\Api\V1\SecurityController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'security'
    ],
    function () {
        Route::post('/register', [SecurityController::class, 'register']);
        Route::post('/login', [SecurityController::class, 'login']);
    }
);
