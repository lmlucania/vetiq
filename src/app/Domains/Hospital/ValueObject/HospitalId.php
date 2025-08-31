<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObject;

class HospitalId
{
    public function __construct(
        private int $hospitalId,
    ) {
    }

    public function getValue(): int
    {
        return $this->hospitalId;
    }
}
