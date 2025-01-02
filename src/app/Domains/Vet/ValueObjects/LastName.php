<?php

declare(strict_types=1);

namespace App\Domains\Vet\ValueObjects;

final class LastName
{
    public function __construct(
        private string $lastName
    ) {
    }

    public function getValue(): string
    {
        return $this->lastName;
    }
}
