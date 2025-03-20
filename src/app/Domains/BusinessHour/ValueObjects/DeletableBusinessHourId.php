<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\ValueObjects;

final class DeletableBusinessHourId
{
    private function __construct(
        private int $id
    ) {
    }

    public static function fromBusinessHourId(BusinessHourId $businessHourId):self
    {
        return new self($businessHourId->getValue());
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
