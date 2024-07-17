<?php

namespace App\Traits;

use Carbon\Carbon;

trait CurrentDayTrait
{
    public function getDayStartAndEnd(Carbon $datetime, string $format = 'Y-m-d H:i:s'): array
    {
        return [
            $datetime->startOfDay()->format($format),
            $datetime->endOfDay()->format($format)
        ];
    }

}
