<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\ValueObjects;

use Carbon\Carbon;

final class Date
{
    public function __construct(
        private Carbon $date
    ) {
    }

    public function getValue(): Carbon
    {
        return $this->date;
    }
}
