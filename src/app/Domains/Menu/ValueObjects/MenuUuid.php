<?php

declare(strict_types=1);

namespace App\Domains\Menu\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class MenuUuid
{
    public function __construct(
        private string $uuid
    ) {
        if (! Uuid::isValid($uuid)) {
            throw new InvalidArgumentException(self::class);
        }
    }

    public function getValue(): string
    {
        return $this->uuid;
    }
}
