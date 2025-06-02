<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

final class DeletableExceptionHourId
{
    private function __construct(
        private int $id
    ) {
    }

    public static function fromBusinessHourId(ExceptionHourId $exceptionHourId):self
    {
        return new self($exceptionHourId->getValue());
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
