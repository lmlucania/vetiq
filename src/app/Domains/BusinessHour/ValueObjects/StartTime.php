<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\ValueObjects;

use Carbon\Carbon;

final class StartTime
{
    public function __construct(
        private Carbon $startTime
    ) {
    }

    public function getValue(): Carbon
    {
        return $this->startTime;
    }
}
