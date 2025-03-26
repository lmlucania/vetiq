<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Enum;

use App\Exceptions\InvalidArgumentException;

/**
 * @OA\Schema(
 *     schema="Enum/DayOfWeek",
 *     type="integer",
 *     description="曜日",
 *     enum={"SUNDAY:0", "MONDAY:1", "TUESDAY:2", "WEDNESDAY:3", "THURSDAY:4", "FRIDAY:5", "SATURDAY:6"},
 *     example=0
 * )
 */
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

    public static function fromInt(int $value): self
    {
        return match ($value) {
            0       => self::SUNDAY,
            1       => self::MONDAY,
            2       => self::TUESDAY,
            3       => self::WEDNESDAY,
            4       => self::THURSDAY,
            5       => self::FRIDAY,
            6       => self::SATURDAY,
            default => throw new InvalidArgumentException('曜日の値が不正です。'),
        };
    }
}
