<?php

namespace App\Http\Responses;

class ApiErrorResponse
{
    private int $code;
    private string $code_text;
    private string $message;
    private string $request;
    public static array $HTTP_CODES = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error'
    ];

    /**
     * @param int $code
     * @param string $code_text
     * @param string $message
     */
    public function __construct(int $code = 500, string $code_text = 'Unknown Server Error', string $message = '')
    {
        $this->code = $code;
        $this->code_text = array_key_exists($code, self::$HTTP_CODES) ? self::$HTTP_CODES[$code] : $code_text;
        $this->message = $message;
    }


}
