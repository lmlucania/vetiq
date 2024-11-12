<?php

declare(strict_types=1);

namespace App\Domains\Menu\ValueObjects;

final class RequiredTime
{
    public function __construct(
        private int $requiredTime
    ) {
    }

    public function getValue(): int
    {
        return $this->requiredTime;
    }
}
