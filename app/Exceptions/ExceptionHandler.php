<?php

namespace App\Exceptions;

use App\Http\Responses\ApiFailResponse;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class ExceptionHandler
{
    public function __invoke(Exceptions $exceptions): void
    {
        $exceptions->render(function (Throwable $throwable) {

            if ($throwable instanceof ValidationException) {
                return new ApiFailResponse(['message' => $throwable->getMessage()], 422);
            }

            if ($throwable instanceof InvalidArgumentException) {
                return new ApiFailResponse([
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine()
                ], 400);
            }

            if ($throwable instanceof NotFoundHttpException) {
                return new ApiFailResponse([
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine()
                ], 404);
            }

            return new ApiFailResponse([
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ], 500);
        });
    }
}
