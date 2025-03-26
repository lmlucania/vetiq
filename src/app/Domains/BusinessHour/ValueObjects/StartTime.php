<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Carbon\Carbon;

final class StartTime
{
    private function __construct(
        private Carbon $startTime
    ) {
    }

    public static function fromCarbon(Carbon $startTime): self
    {
        return new self($startTime);
    }

    public static function fromString(string $startTime): self
    {
        $carbonTime = Carbon::createFromFormat('H:i', $startTime);

        if (! $carbonTime || $carbonTime->format('H:i') !== $startTime) {
            throw new InvalidArgumentException('受付開始時間のフォーマットが不正です。');
        }

        return new self($carbonTime);
    }

    public function getValue(): Carbon
    {
        return $this->startTime;
    }
}
