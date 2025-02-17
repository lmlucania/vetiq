<?php

declare(strict_types=1);

namespace App\Domains\Vet\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class VetUuid
{
    public function __construct(
        private string $uuid
    ) {
        if (! Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('獣医師UUIDが不正です');
        }
    }

    public function getValue(): string
    {
        return $this->uuid;
    }
}
