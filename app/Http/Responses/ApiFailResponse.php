<?php

namespace App\Http\Responses;

use App\Enums\StatusCodeEnum;

class ApiFailResponse extends ApiResponse
{

    protected function setStatusCodes(): void
    {
        $this->statusCodes = [
            400 => StatusCodeEnum::HTTP_BAD_REQUEST,
            401 => StatusCodeEnum::HTTP_UNAUTHORIZED,
            403 => StatusCodeEnum::HTTP_FORBIDDEN,
            404 => StatusCodeEnum::HTTP_NOT_FOUND,
            405 => StatusCodeEnum::HTTP_METHOD_NOT_ALLOWED,
            422 => StatusCodeEnum::HTTP_UNPROCESSABLE_ENTITY,
            429 => StatusCodeEnum::HTTP_TOO_MANY_REQUESTS,
            500 => StatusCodeEnum::HTTP_INTERNAL_SERVER_ERROR,
            503 => StatusCodeEnum::HTTP_SERVICE_UNAVAILABLE
        ];
    }
}
