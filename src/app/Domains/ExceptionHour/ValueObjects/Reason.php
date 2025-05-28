<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

final class Reason
{
    public function __construct(
        private string $reason
    ) {
    }

    public function getValue(): string
    {
        return $this->reason;
    }
}
