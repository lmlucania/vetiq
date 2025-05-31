<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Entity;

use App\Domains\ExceptionHour\ValueObjects\Date;
use App\Domains\ExceptionHour\ValueObjects\EndTime;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourId;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourUuid;
use App\Domains\ExceptionHour\ValueObjects\IsClosed;
use App\Domains\ExceptionHour\ValueObjects\Reason;
use App\Domains\ExceptionHour\ValueObjects\StartTime;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\DomainException;

class ExceptionHour
{
    public function __construct(
        private ExceptionHourId $exceptionHourId,
        private ExceptionHourUuid $exceptionHourUuid,
        private HospitalId $hospitalId,
        private Date $date,
        private ?StartTime $startTime,
        private ?EndTime $endTime,
        private IsClosed $isClosed,
        private ?Reason $reason,
    ) {
        if ($this->isClosed->getValue()) {
            if (!is_null($this->startTime) || !is_null($this->endTime)) {
                throw new DomainException('休診の場合は、開始時刻と終了時刻を指定してはいけません。');
            }
        } else {
            if (is_null($this->startTime) || is_null($this->endTime)) {
                throw new DomainException('休診でない場合は、開始時刻と終了時刻を入力してください。');
            }

            // 開始 < 終了 チェック
            if ($this->startTime->getValue() > $this->endTime->getValue()) {
                throw new DomainException('開始時刻は終了時刻より前にしてください。');
            }
        }
    }

    public function getExceptionHourId(): ExceptionHourId
    {
        return $this->exceptionHourId;
    }

    public function getExceptionHourUuid(): ExceptionHourUuid
    {
        return $this->exceptionHourUuid;
    }

    public function getHospitalId(): HospitalId
    {
        return $this->hospitalId;
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
