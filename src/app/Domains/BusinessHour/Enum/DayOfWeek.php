<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Enum;

use App\Exceptions\InvalidArgumentException;

enum DayOfWeek: int
{
    case SUNDAY    = 0;
    case MONDAY    = 1;
    case TUESDAY   = 2;
    case WEDNESDAY = 3;
    case THURSDAY  = 4;
    case FRIDAY    = 5;
    case SATURDAY  = 6;

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

    public static function fromInt(int $value): self
    {
        return self::tryFrom($value) ?? throw new InvalidArgumentException("曜日の値が不正です: $value");
    }
}
