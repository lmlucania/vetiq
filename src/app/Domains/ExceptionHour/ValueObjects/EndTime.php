<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Carbon\Carbon;

final class EndTime
{
    private function __construct(
        private Carbon $endTime
    ) {
    }

    public static function fromCarbon(Carbon $endTime): self
    {
        return new self($endTime);
    }

    public static function fromString(string $endTime): self
    {
        $carbonTime = Carbon::createFromFormat('H:i', $endTime);

        if (! $carbonTime || $carbonTime->format('H:i') !== $endTime) {
            throw new InvalidArgumentException('例外受付終了時間のフォーマットが不正です。');
        }

        return new self($carbonTime);
    }

    public function getValue(): Carbon
    {
        return $this->endTime;
    }
}
