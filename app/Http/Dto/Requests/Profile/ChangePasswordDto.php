<?php

namespace App\Http\Dto\Requests\Profile;

use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;

class ChangePasswordDto extends AbstractDto implements DtoInterface
{
    public string $currentPassword;
    public string $newPassword;

    public function rules(): array
    {
        return [
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'currentPassword.required' => __('validation.required', ['attribute' => 'currentPassword']),
            'currentPassword.string' => __('validation.string', ['attribute' => 'currentPassword']),

            'newPassword.required' => __('validation.required', ['attribute' => 'newPassword']),
            'newPassword.string' => __('validation.string', ['attribute' => 'newPassword']),
            'newPassword.min' => __('validation.min', ['attribute' => 'newPassword', 'size' => '8']),
            'newPassword.confirmed' => __('validation.confirmed', ['attribute' => 'newPassword']),
        ];
    }
}
