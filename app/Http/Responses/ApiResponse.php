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
            throw new InvalidArgumentException(__('exceptions.code_response_match'));
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

    private function prepareStructure($responseStructure)
    {
        if ($this->message) {
            $responseStructure['message'] = $this->message;
        }

        $dataKey = $this instanceof ApiSuccessResponse ? 'data' : 'errors';

        if ($this->data) {
            $responseStructure[$dataKey] = $this->data;
        }

        return $responseStructure;
    }

    private function prepareModule(): void
    {
        $applicationName = config('app.name');
        $applicationName = str_replace('-', '_', $applicationName);

        $path = request()->getPathInfo();

        if (str_contains($path, '?')) {
            $path = substr($path, 0, strpos($path, '?'));
        }

        $path = str_replace(['/', '-'], '_', $path);
        $path = substr($path, 4);

        $this->module = $applicationName . $path;
    }
}
