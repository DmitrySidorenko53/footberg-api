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

            $data = [
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                //'trace' => $throwable->getTrace(),
            ];

            if ($throwable instanceof ValidationException) {
                return new ApiFailResponse([$throwable->errors()], 422);
            }

            if ($throwable instanceof InvalidArgumentException || $throwable instanceof InvalidIncomeTypeException) {
                return new ApiFailResponse($data, 400);
            }

            if ($throwable instanceof NotFoundHttpException) {
                return new ApiFailResponse($data, 404);
            }

            if ($throwable instanceof TooManyRequestsException) {
                return new ApiFailResponse($data, 429);
            }

            return new ApiFailResponse($data, 500);
        });
    }
}
