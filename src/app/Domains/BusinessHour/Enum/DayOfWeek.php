<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Enum;

enum DayOfWeek: int
{
    case SUNDAY    = 0;
    case MONDAY    = 1;
    case TUESDAY   = 2;
    case WEDNESDAY = 3;
    case THURSDAY  = 4;
    case FRIDAY    = 5;
    case SATURDAY  = 6;

    public function name(): string
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
