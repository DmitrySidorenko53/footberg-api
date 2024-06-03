<?php

namespace App\Http\Dto\Requests;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Validation\ValidationException;

abstract class AbstractDto implements DtoInterface, ValidatesWhenResolved
{
    use ValidatesWhenResolvedTrait;

    protected $data;
    protected $validator;
    protected bool $stopOnFirstFailure = false;

    /**
     * @throws ValidationException
     */
    public function validateResolved(): void
    {
        $instance = $this->getValidatorInstance();

        if ($instance->fails()) {
            $this->failedValidation($instance);
        }

        $this->passedValidation();
    }

    protected function getValidatorInstance()
    {
        if ($this->validator) {
            return $this->validator;
        }
        $factory = App::make(ValidationFactory::class);
        $validator = $this->createDefaultValidator($factory);

        $this->setValidator($validator);

        return $this->validator;
    }

    private function createDefaultValidator(mixed $factory)
    {
        $rules = $this->rules();
        $messages = $this->messages();

        return $factory->make(
            $this->validationData(),
            $rules,
            $messages
        )->stopOnFirstFailure($this->stopOnFirstFailure);
    }

    public function validationData(): array
    {
        return request()->all();
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;
        return $this;
    }

    protected function failedValidation(Validator $validator)
    {
        $exception = $validator->getException();
        throw (new $exception($validator));
    }

    private function passedValidation()
    {
        //TODO set to dto fields value of validated data
    }
}
