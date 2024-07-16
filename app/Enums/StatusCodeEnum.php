<?php

namespace App\Enums;

enum StatusCodeEnum: string
{
    case HTTP_OK = 'ok';
    case HTTP_CREATED = 'created';
    case HTTP_ACCEPTED = 'accepted';
    case HTTP_NO_CONTENT = 'no_content';

    case HTTP_BAD_REQUEST = 'bad_request';
    case HTTP_UNAUTHORIZED = 'unauthorized';
    case HTTP_FORBIDDEN = 'forbidden';
    case HTTP_NOT_FOUND = 'not_found';
    case HTTP_METHOD_NOT_ALLOWED = 'method_not_allowed';
    case HTTP_UNPROCESSABLE_ENTITY = 'unprocessable_entity';
    case HTTP_TOO_MANY_REQUESTS = 'too_many_requests';
    case HTTP_INTERNAL_SERVER_ERROR = 'internal_server_error';
    case HTTP_SERVICE_UNAVAILABLE = 'service_unavailable';
}
