<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Carbon\CarbonImmutable;
use Throwable;

class Date
{
    private CarbonImmutable $date;

    public function __construct(string $date)
    {
        try {
            $parsed = CarbonImmutable::createFromFormat('Y-m-d', $date);

            if (! $parsed || $parsed->format('Y-m-d') !== $date) {
                throw new InvalidArgumentException("日付の形式が不正です: {$date}");
            }

            $this->date = $parsed;
        } catch (Throwable $e) {
            throw new InvalidArgumentException("日付の解析に失敗しました: {$date}");
        }
    }

    public function getValue(): CarbonImmutable
    {
        return $this->date;
    }
}
