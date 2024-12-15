<?php

declare(strict_types=1);

namespace App\Domains\Menu\ValueObjects;

final class Detail
{
    public function __construct(
        private string $detail
    ) {
    }

    public function getValue(): string
    {
        return $this->detail;
    }
}
