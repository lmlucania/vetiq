<?php

declare(strict_types=1);

namespace App\Domains\Pet\Enum;

enum NeuteringStatus: int
{
    case Intact  = 1;
    case Done    = 2;
    case Unknown = 9;

    public function label(): string
    {
        return match ($this) {
            self::Intact  => '未実施',
            self::Done    => '実施済み',
            self::Unknown => '不明',
        };
    }
}
