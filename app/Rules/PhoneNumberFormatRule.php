<?php

namespace App\Rules;

use App\Traits\PhoneNumberTrait;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PhoneNumberFormatRule implements ValidationRule, DataAwareRule
{
    use PhoneNumberTrait;

    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $country = strtoupper($this->data['countryCode']);

        if (!$this->isValidPhoneNumber($country, $value)) {
            $fail(__('validation.invalid_phone_number'));
        }
    }



    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }
}
