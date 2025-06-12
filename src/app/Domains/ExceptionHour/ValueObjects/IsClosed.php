<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

class IsClosed
{
    public function __construct(
        private bool $isClose
    ) {
    }

    public function getValue(): bool
    {
        return $this->isClose;
    }
}
