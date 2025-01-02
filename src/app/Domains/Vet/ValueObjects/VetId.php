<?php

declare(strict_types=1);

namespace App\Domains\Vet\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class VetId
{
    public function __construct(
        private int $id
    ) {
        if ($id < 1) {
            throw new InvalidArgumentException('獣医師IDが不正です');
        }
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
