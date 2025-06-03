<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Enum;

use App\Exceptions\InvalidArgumentException;

enum TimePeriod: int
{
    case AM = 0;
    case PM = 1;

    public function label(): string
    {
        return match ($this) {
            self::AM => '午前',
            self::PM => '午後',
        };
    }

    public static function fromInt(int $value): self
    {
        return self::tryFrom($value) ?? throw new InvalidArgumentException("午前午後の値が不正です: $value");
    }
}
