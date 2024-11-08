<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObjects;

final class Address
{
    public function __construct(
        private string $address
    ) {
    }

    public function getValue(): string
    {
        return $this->address;
    }
}
