<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Enum;

use App\Exceptions\InvalidArgumentException;

/**
 * @OA\Schema(
 *     schema="Enum/TimePeriod",
 *     type="integer",
 *     description="午前・午後",
 *     enum={"AM:0", "PM:1"},
 *     example=0
 * )
 */
enum TimePeriod: int
{
    case AM = 0;
    case PM = 1;

    public function name(): string
    {
        return match ($this) {
            self::AM => '午前',
            self::PM => '午後',
        };
    }

    public static function fromInt(int $value): self
    {
        return match ($value) {
            0       => self::AM,
            1       => self::PM,
            default => throw new InvalidArgumentException('午前午後の値が不正です。'),
        };
    }
}
