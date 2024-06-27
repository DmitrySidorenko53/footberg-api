<?php

namespace App\Helpers;

use App\Enums\EmailScopeEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Models\MailPattern;

final class EmailContentHelper
{
    /**
     * @throws InvalidIncomeTypeException
     */
    public static function build(array $data, $scope): array
    {
        if (!$scope instanceof EmailScopeEnum) {
            throw new InvalidIncomeTypeException(__METHOD__, EmailScopeEnum::class);
        }

        $view = $scope->value;
        $scope = strtolower($scope->name);

        /** @var MailPattern $pattern */
        $pattern = MailPattern::query()->where('scope', $scope)->first();

        return [
            'recipient' => $data['recipient'],
            'subject' => $pattern->subject,
            'title' => $pattern->title,
            'body' => $pattern->body,
            'footer' => $pattern->footer,
            'additional_data' => $data['code'],
            'view' => $view,
        ];
    }
}
