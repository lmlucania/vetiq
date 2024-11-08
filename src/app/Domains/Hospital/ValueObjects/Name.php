<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObjects;

final class Name
{
    public function __construct(
        private string $name
    ) {
    }

    public function getValue(): string
    {
        return $this->name;
    }
}
