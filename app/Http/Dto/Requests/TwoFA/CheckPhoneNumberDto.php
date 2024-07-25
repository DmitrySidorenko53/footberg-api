<?php

namespace App\Http\Dto\Requests\TwoFA;

use App\Enums\CountryPhonePrefixEnum;
use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Rules\PhoneNumberFormatRule;
use App\Traits\EnumKeysTrait;
use Illuminate\Validation\Rule;

class CheckPhoneNumberDto extends AbstractDto implements DtoInterface
{
    use EnumKeysTrait;

    public string $countryCode;
    public string $number;

    public function rules(): array
    {
        $countryCodes = $this->keys(CountryPhonePrefixEnum::cases(), true);

        return [
            'countryCode' => ['required', 'string', Rule::in($countryCodes)],
            'number' => ['required', 'string', new PhoneNumberFormatRule],
        ];
    }

    public function messages(): array
    {
        $countryCodesString = $this->keys(CountryPhonePrefixEnum::cases(), true, true);

        return [
            'countryCode.required' => __('validation.required', ['attribute' => 'countryCode']),
            'countryCode.string' => __('validation.string', ['attribute' => 'countryCode']),
            'countryCode.in' => __('validation.in_array', ['array' => $countryCodesString]),

            'number.required' => __('validation.required', ['attribute' => 'number']),
            'number.string' => __('validation.string', ['attribute' => 'number']),
        ];
    }
}
