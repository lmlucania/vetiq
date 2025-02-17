<?php

declare(strict_types=1);

namespace App\Domains\Vet\ValueObjects;

final class DeletableVetId
{
    private function __construct(
        private int $id
    ) {
    }

    /**
     * @param VetId $vetId
     * @return self
     */
    public static function fromVetId(VetId $vetId):self
    {
        return new self($vetId->getValue());
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
