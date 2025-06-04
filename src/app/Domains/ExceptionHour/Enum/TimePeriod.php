<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Enum;

use App\Exceptions\InvalidArgumentException;

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

    public static function fromInt(int $value): self
    {
        return self::tryFrom($value) ?? throw new InvalidArgumentException("午前午後の値が不正です: $value");
    }
}
