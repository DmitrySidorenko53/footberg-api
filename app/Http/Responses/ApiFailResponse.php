<?php

namespace App\Http\Responses;

use App\Enums\StatusCode;
use InvalidArgumentException;

class ApiFailResponse extends ApiResponse
{

    protected function setStatusCodes(): void
    {
        $this->statusCodes = [
            400 => StatusCode::HTTP_BAD_REQUEST,
            401 => StatusCode::HTTP_UNAUTHORIZED,
            403 => StatusCode::HTTP_FORBIDDEN,
            404 => StatusCode::HTTP_NOT_FOUND,
            405 => StatusCode::HTTP_METHOD_NOT_ALLOWED,
            422 => StatusCode::HTTP_UNPROCESSABLE_ENTITY,
            500 => StatusCode::HTTP_INTERNAL_SERVER_ERROR,
            503 => StatusCode::HTTP_SERVICE_UNAVAILABLE
        ];
    }
}
