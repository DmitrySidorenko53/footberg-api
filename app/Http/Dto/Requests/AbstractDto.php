<?php

namespace App\Http\Dto\Requests;

use App\Http\Dto\DtoInterface;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Validation\ValidationException;

abstract class AbstractDto implements DtoInterface
{
    use ValidatesWhenResolvedTrait;

    protected $data;
    protected $validator;
    protected bool $stopOnFirstFailure = true;

    public function __construct()
    {
        $this->setData();
        $validator = $this->getValidatorInstance();
        $this->setValidator($validator);
    }

    protected function getValidatorInstance(): \Illuminate\Validation\Validator
    {
        if ($this->validator) {
            return $this->validator;
        }
        return $this->createValidator();
    }

    private function createValidator(): \Illuminate\Validation\Validator
    {
        $rules = $this->rules();
        $messages = $this->messages();
        return ValidatorFactory::make(
            $this->data,
            $rules,
            $messages
        )->stopOnFirstFailure($this->stopOnFirstFailure);
    }

    /**
     * @param mixed $validator
     */
    public function setValidator(mixed $validator): void
    {
        $this->validator = $validator;
    }


    public function setData(): void
    {
        $this->data = Request::all();
    }

    public function validated($key = null): array
    {
        return data_get($this->validator->validated(), $key);
    }

    protected function failedValidation(Validator $validator): void
    {
        $exception = $validator->getException();
        if ($exception || empty($this->validated())) {
            throw (new ValidationException($validator));
        }
    }
}
