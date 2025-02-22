<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\ValueObjects;

use Carbon\Carbon;

final class EndTime
{
    public function __construct(
        private Carbon $endTime
    ) {
    }

    public function getValue(): Carbon
    {
        return $this->endTime;
    }
}
