<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

final class IsClosed
{
    public function __construct(
        private bool $isClosed
    ) {
    }

    public function getValue(): bool
    {
        return $this->isClosed;
    }
}
