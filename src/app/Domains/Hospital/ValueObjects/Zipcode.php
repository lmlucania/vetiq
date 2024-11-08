<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class Zipcode
{
    public function __construct(
        private string $zipcode
    ) {
        if (! preg_match('/^\d{7}$/', $zipcode)) {
            throw new InvalidArgumentException('zip code');
        }
    }

    public function getValue(): string
    {
        return $this->zipcode;
    }
}
