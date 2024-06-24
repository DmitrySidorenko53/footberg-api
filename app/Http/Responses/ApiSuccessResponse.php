<?php

namespace App\Http\Responses;

use App\Enums\StatusCode;

class ApiSuccessResponse extends ApiResponse
{

    protected function setStatusCodes(): void
    {
        $this->statusCodes = [
            200 => StatusCode::HTTP_OK,
            201 => StatusCode::HTTP_CREATED,
            202 => StatusCode::HTTP_ACCEPTED,
            204 => StatusCode::HTTP_NO_CONTENT,
        ];
    }
}
