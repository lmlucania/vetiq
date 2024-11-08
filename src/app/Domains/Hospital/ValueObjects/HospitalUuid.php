<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class HospitalUuid
{
    public function __construct(
        private string $uuid
    ) {
        if (! Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('hospital uuid');
        }
    }

    public function getValue(): string
    {
        return $this->uuid;
    }
}
