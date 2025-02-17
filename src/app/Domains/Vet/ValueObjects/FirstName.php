<?php

declare(strict_types=1);

namespace App\Domains\Vet\ValueObjects;

final class FirstName
{
    public function __construct(
        private string $firstName
    ) {
    }

    public function getValue(): string
    {
        return $this->firstName;
    }
}
