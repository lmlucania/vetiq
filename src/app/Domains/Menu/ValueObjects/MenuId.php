<?php

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
