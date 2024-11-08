<?php

declare(strict_types=1);

namespace App\Domains\Staff\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class Email
{
    public function __construct(
        private string $email
    ) {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('email');
        }
    }

    public function getValue(): string
    {
        return $this->email;
    }
}
