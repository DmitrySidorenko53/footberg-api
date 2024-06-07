<?php

namespace App\Exceptions;

use App\Http\Responses\ApiFailResponse;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Throwable;

final class Handler
{
    public static function handle(Exceptions $exceptions): void
    {
        $exceptions->render(function (Throwable $throwable) {
            if ($throwable instanceof ValidationException) {
                return new ApiFailResponse($throwable->errors(), 422, 'Validation Error');
            }
            if ($throwable instanceof InvalidArgumentException) {
                return new ApiFailResponse([
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine()
                ], 400, 'Bad Request');
            }
            if ($throwable instanceof RepositoryException) {
                return new ApiFailResponse([$throwable->getMessage()], 500, 'Database Error');
            }
            return new ApiFailResponse([$throwable->getMessage()], 500);
        });
    }
}
