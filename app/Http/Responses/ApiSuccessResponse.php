<?php

namespace App\Http\Responses;

use App\Enums\StatusCodeEnum;

class ApiSuccessResponse extends ApiResponse
{

    protected function setStatusCodes(): void
    {
        $this->statusCodes = [
            200 => StatusCodeEnum::HTTP_OK,
            201 => StatusCodeEnum::HTTP_CREATED,
            202 => StatusCodeEnum::HTTP_ACCEPTED,
            204 => StatusCodeEnum::HTTP_NO_CONTENT,
        ];
    }
}
