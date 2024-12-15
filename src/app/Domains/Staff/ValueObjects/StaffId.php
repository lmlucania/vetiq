<?php

declare(strict_types=1);

namespace App\Domains\Staff\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class StaffId
{
    public function __construct(
        private int $staffId
    ) {
        if ($staffId < 1) {
            throw new InvalidArgumentException(get_class($this));
        }
    }

    public function getValue(): int
    {
        return $this->staffId;
    }
}
