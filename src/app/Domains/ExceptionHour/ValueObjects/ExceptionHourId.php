<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class ExceptionHourId
{
    public function __construct(
        private int $id
    ) {
        if ($id < 1) {
            throw new InvalidArgumentException('例外受付時間IDが不正です');
        }
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
