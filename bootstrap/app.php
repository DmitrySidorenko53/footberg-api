<?php

use App\Http\Responses\ApiFailResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $throwable) {
           if ($throwable instanceof ValidationException) {
               return new ApiFailResponse($throwable->errors(), 422, 'Validation Error');
           }
           if ($throwable instanceof InvalidArgumentException) {
               return new ApiFailResponse([$throwable->getMessage()], 400, 'Invalid Request');
           }
           return new ApiFailResponse([$throwable->getMessage()], 500);
        });
    })->create();
