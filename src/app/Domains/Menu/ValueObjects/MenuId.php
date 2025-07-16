<?php

declare(strict_types=1);

namespace App\Domains\Menu\ValueObjects;

class MenuId
{
    public function __construct(
        private int $id,
    ) {
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
