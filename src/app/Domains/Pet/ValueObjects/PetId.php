<?php

declare(strict_types=1);

namespace App\Domains\Pet\ValueObjects;

class PetId
{
    public function __construct(
        private int $id,
    ) {
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
