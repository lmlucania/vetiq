<?php

declare(strict_types=1);

namespace App\Domains\Schedule\Enum;

enum TimePeriod: int
{
    case AM    = 1;
    case PM    = 2;
    case NIGHT = 3;

    public function label(): string
    {
        return match ($this) {
            self::AM    => '午前',
            self::PM    => '午後',
            self::NIGHT => '夜間',
        };
    }
}
