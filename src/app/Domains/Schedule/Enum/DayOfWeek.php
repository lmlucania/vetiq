<?php

declare(strict_types=1);

namespace App\Domains\Schedule\Enum;

enum DayOfWeek: int
{
    case SUNDAY    = 1;
    case MONDAY    = 2;
    case TUESDAY   = 3;
    case WEDNESDAY = 4;
    case THURSDAY  = 5;
    case FRIDAY    = 6;
    case SATURDAY  = 7;

    public function label(): string
    {
        return match ($this) {
            self::SUNDAY    => '日',
            self::MONDAY    => '月',
            self::TUESDAY   => '火',
            self::WEDNESDAY => '水',
            self::THURSDAY  => '木',
            self::FRIDAY    => '金',
            self::SATURDAY  => '土',
        };
    }
}
