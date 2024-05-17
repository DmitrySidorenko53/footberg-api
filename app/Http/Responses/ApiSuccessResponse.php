<?php

namespace App\Http\Responses;

class ApiSuccessResponse
{
    private int $code;
    private string $code_text;
    private string $message;

    private array $data;

    public static array $HTTP_CODES = [
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted'
    ];

    /**
     * @param int $code
     * @param string $code_text
     * @param string $message
     * @param array $data
     */
    public function __construct(int $code, string $code_text, string $message, array $data)
    {
        $this->code = $code;
        $this->code_text =  array_key_exists($code, self::$HTTP_CODES) ? self::$HTTP_CODES[$code] : $code_text;
        $this->message = $message;
        $this->data = $data;
    }

}
