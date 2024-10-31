<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObjects;

use App\Exceptions\InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class PublicId
{
    public function __construct(
        private string $publicId
    )
    {
        if (! Uuid::isValid($publicId)) {
            throw new InvalidArgumentException("public id");
        }

    }

    public function getValue(): string
    {
        return $this->publicId;
    }
}
