<?php

namespace App\Helpers;

final class StringGenerator
{
    public static function generateSecurityToken($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*?';
        $charactersLength = strlen($characters);
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        return $token;
    }

    public static function getSecurityTokenStart(): string
    {
        $application = config('app.name');
        $application = trim(strtolower($application));
        $application = str_replace('-', '_', $application);
        return $application . '_auth_key:';
    }
}