<?php

declare(strict_types=1);

namespace App\Domains\Review\Enum;

use App\Exceptions\InvalidArgumentException;

enum Rating: int
{
    case Zero  = 0;
    case One   = 1;
    case Two   = 2;
    case Three = 3;
    case Four  = 4;
    case Five  = 5;

    public static function fromInt(int $value): self
    {
        return self::tryFrom($value) ?? throw new InvalidArgumentException("評価点の値が不正です: $value");
    }
}
