<?php

declare(strict_types=1);

namespace App\Domains\Menu\ValueObjects;

final class IsPublished
{
    public function __construct(
        private bool $isPublished
    ) {
    }

    public function getValue(): bool
    {
        return $this->isPublished;
    }
}
