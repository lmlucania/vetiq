<?php

declare(strict_types=1);

namespace App\Application\Dto\Response;

use App\Domains\ExceptionHour\ValueObjects\Date;
use App\Domains\ExceptionHour\ValueObjects\EndTime;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourUuid;
use App\Domains\ExceptionHour\ValueObjects\IsClosed;
use App\Domains\ExceptionHour\ValueObjects\Reason;
use App\Domains\ExceptionHour\ValueObjects\StartTime;

class ExceptionHourDto
{
    public function __construct(
        private ExceptionHourUuid $exceptionHourUuid,
        private Date $date,
        private ?StartTime $startTime,
        private ?EndTime $endTime,
        private IsClosed $isClosed,
        private ?Reason $reason,
    ) {
    }

    public function getExceptionHourUuid(): ExceptionHourUuid
    {
        return $this->exceptionHourUuid;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function getStartTime(): ?StartTime
    {
        return $this->startTime;
    }

    public function getEndTime(): ?EndTime
    {
        return $this->endTime;
    }

    public function getIsClosed(): IsClosed
    {
        return $this->isClosed;
    }

    public function getReason(): ?Reason
    {
        return $this->reason;
    }
}
