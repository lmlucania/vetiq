<?php

declare(strict_types=1);

namespace App\Domains\Menu\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class MenuId
{
    public function __construct(
        private int $id
    ) {
        if ($id < 1) {
            throw new InvalidArgumentException(self::class);
        }
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
