<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

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
