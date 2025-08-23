<?php

declare(strict_types=1);

namespace App\Domains\Schedule\Enum;

enum DayOfWeek: int
{
    case MONDAY    = 1;
    case TUESDAY   = 2;
    case WEDNESDAY = 3;
    case THURSDAY  = 4;
    case FRIDAY    = 5;
    case SATURDAY  = 6;
    case SUNDAY    = 7;

    public function label(): string
    {
        return match ($this) {
            self::MONDAY    => '月',
            self::TUESDAY   => '火',
            self::WEDNESDAY => '水',
            self::THURSDAY  => '木',
            self::FRIDAY    => '金',
            self::SATURDAY  => '土',
            self::SUNDAY    => '日',
        };
    }
}
