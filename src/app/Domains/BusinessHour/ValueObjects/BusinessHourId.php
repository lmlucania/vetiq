<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class BusinessHourId
{
    public function __construct(
        private int $id
    ) {
        if ($id < 1) {
            throw new InvalidArgumentException('予約受付時間IDが不正です');
        }
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
