<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class HospitalId
{
    public function __construct(
        private int $hospitalId
    ) {
        if ($hospitalId < 1) {
            throw new InvalidArgumentException('病院IDが不正です');
        }
    }

    public function getValue(): int
    {
        return $this->hospitalId;
    }
}
