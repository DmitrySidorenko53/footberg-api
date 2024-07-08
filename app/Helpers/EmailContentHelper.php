<?php

namespace App\Helpers;

use App\Enums\EmailScopeEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Response\Security\CodeDto;
use App\Models\MailPattern;

final class EmailContentHelper
{
    /**
     * @throws InvalidIncomeTypeException
     */
    public static function build($data, $scope): array
    {
        if (!$data instanceof CodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, CodeDto::class);
        }

        if (!$scope instanceof EmailScopeEnum) {
            throw new InvalidIncomeTypeException(__METHOD__, EmailScopeEnum::class);
        }

        $view = $scope->value;
        $scope = strtolower($scope->name);

        /** @var MailPattern $pattern */
        $pattern = MailPattern::query()->where('scope', $scope)->first();

        $additionalData = ($scope == strtolower(EmailScopeEnum::CONFIRMATION->name))
            ? $data->confirmation : $data->reset_password;

        return [
            'recipient' => $data->recipient,
            'subject' => $pattern->subject,
            'title' => $pattern->title,
            'body' => $pattern->body,
            'footer' => $pattern->footer,
            'additional_data' => $additionalData,
            'view' => $view,
        ];
    }
}
