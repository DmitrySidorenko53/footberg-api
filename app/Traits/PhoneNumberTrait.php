<?php

namespace App\Traits;

use App\Enums\CountryPhonePrefixEnum;

trait PhoneNumberTrait
{

    /**
     * @param string $country
     * @param mixed $value
     * @return bool
     */
    public function isValidPhoneNumber(string $country, mixed $value): bool
    {
        list($value, $countryPhonePrefix, $neededLength) = $this->preparePhoneNumber($country, $value);

        $numeric = preg_match('/[0-9]+/i', $value);

        $validLength = (strlen($value) + strlen($countryPhonePrefix) === $neededLength);

        return $validLength && $numeric;
    }

    /**
     * @param $country
     * @param mixed $value
     * @param bool $forSaving
     * @return array|string
     */
    public function preparePhoneNumber($country, mixed $value, bool $forSaving = false): array|string
    {

        $countryPhonePrefix = CountryPhonePrefixEnum::getPrefix($country);

        $lengthsOfPhoneNumbers = [
            CountryPhonePrefixEnum::BLR->name => 13,
            CountryPhonePrefixEnum::RU->name => 12,
            CountryPhonePrefixEnum::KZ->name => 11
        ];

        $neededLength = $lengthsOfPhoneNumbers[strtoupper($country)];

        $value = str_replace(' ', '', $value);

        $brackets = ['(', ')'];

        foreach ($brackets as $bracket) {
            if (!str_contains($value, $bracket)) {
                continue;
            }
            $value = str_replace($bracket, '', $value);
        }

        if (str_contains($value, '-')) {
            $value = str_replace('-', '', $value);
        }

        if (!$forSaving) {
            return array($value, $countryPhonePrefix, $neededLength);
        }

        return $countryPhonePrefix . $value;
    }
}
