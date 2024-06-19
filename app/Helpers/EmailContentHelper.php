<?php

namespace App\Helpers;

use App\Enums\EmailScope;
use App\Models\MailPattern;
use InvalidArgumentException;

final class EmailContentHelper
{
    public static function build(array $data, $scope): array
    {
        if (!$scope instanceof EmailScope) {
            throw new InvalidArgumentException('Scope must be an instance of EmailScope');
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
            'additional_data' => $data[$scope],
            'view' => $view,
        ];
    }
}
