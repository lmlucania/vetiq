<?php

declare(strict_types=1);

namespace App\Domains\Schedule\Enum;

enum TimePeriod: int
{
    case AM = 1;
    case PM = 2;

    public function label(): string
    {
        return match ($this) {
            self::AM => '午前',
            self::PM => '午後',
        };
    }
}
