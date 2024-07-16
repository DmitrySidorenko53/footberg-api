<?php

namespace App\Traits;

trait EnumKeysTrait
{
    public function keys($cases, $key = false, $asString = false): array|string
    {
        $keys = [];
        foreach ($cases as $case) {
            $keys[] = $key ? strtolower($case->name) : $case->value;
        }

        return $asString ? implode(',', $keys) : $keys;
    }
}
