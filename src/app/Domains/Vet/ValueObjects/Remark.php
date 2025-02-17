<?php

declare(strict_types=1);

namespace App\Domains\Vet\ValueObjects;

final class Remark
{
    public function __construct(
        private string $remark
    ) {
    }

    public function getValue(): string
    {
        return $this->remark;
    }
}
