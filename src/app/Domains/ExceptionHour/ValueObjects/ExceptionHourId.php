<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

class ExceptionHourId
{
    public function __construct(
        private int $exceptionHourId,
    ) {
    }

    public function getValue(): int
    {
        return $this->exceptionHourId;
    }
}
