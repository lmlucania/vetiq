<?php

namespace App\Domains\Appointment\ValueObjects;

class HospitalMemo
{
    public function __construct(
        private string $hospitalMemo
    ) {
    }

    public function getValue(): string
    {
        return $this->hospitalMemo;
    }
}
