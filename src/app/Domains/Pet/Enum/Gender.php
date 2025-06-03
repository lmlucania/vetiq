<?php

declare(strict_types=1);

namespace App\Domains\Pet\Enum;

use App\Exceptions\InvalidArgumentException;

enum Gender: int
{
    case Male    = 1;
    case Female  = 2;
    case Unknown = 9;

    public function label(): string
    {
        return match ($this) {
            self::Male    => 'オス',
            self::Female  => 'メス',
            self::Unknown => '不明',
        };
    }

    public static function fromInt(int $value): self
    {
        return self::tryFrom($value) ?? throw new InvalidArgumentException("性別の値が不正です: $value");
    }
}
