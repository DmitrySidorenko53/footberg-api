<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

abstract class ApiResponse extends JsonResponse
{
    protected int $statusCode;
    protected string $statusText;
    protected mixed $data;
    protected string $module;
    protected string $message;
    protected array $statusCodes;

    /**
     * @param $data
     * @param int $statusCode
     * @param string $message
     */
    public function __construct($data, int $statusCode, $message = '')
    {
        $this->setStatusCodes();

        if (!array_key_exists($statusCode, $this->statusCodes)) {
            throw new InvalidArgumentException('Status code must match the type of response');
        }

        $this->statusCode = $statusCode;
        $this->statusText = $this->statusCodes[$statusCode]->value;

        $this->prepareModule();

        $this->data = $data;
        $this->message = $message;

        $responseStructure = [
            'code_http' => $this->statusCode,
            'code_text' => $this->statusText,
            'module' => $this->module,
        ];
        $responseStructure = $this->prepareStructure($responseStructure);

        parent::__construct($responseStructure, $statusCode);
    }

    abstract protected function setStatusCodes();
    abstract protected function prepareStructure($responseStructure);

    private function prepareModule(): void
    {
        $applicationName = config('app.name');
        $applicationName = str_replace('-', '_', $applicationName);

        $path = request()->getPathInfo();
        $path = str_replace('/', '_', $path);
        $path = substr($path, 4);

        $this->module = $applicationName . $path;
    }
}