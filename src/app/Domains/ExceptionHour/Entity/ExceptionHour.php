<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Entity;

use App\Domains\ExceptionHour\ValueObjects\Date;
use App\Domains\ExceptionHour\ValueObjects\EndTime;
use App\Domains\ExceptionHour\ValueObjects\ExceptionHourId;
use App\Domains\ExceptionHour\ValueObjects\IsClosed;
use App\Domains\ExceptionHour\ValueObjects\Reason;
use App\Domains\ExceptionHour\ValueObjects\StartTime;
use App\Domains\Hospital\Repositories\ValueObject\HospitalId;
use App\Exceptions\DomainException;

class ExceptionHour
{
    private function __construct(
        private ?ExceptionHourId $exceptionHourId,
        private HospitalId $hospitalId,
        private Date $date,
        private ?StartTime $startTime,
        private ?EndTime $endTime,
        private IsClosed $isClosed,
        private ?Reason $reason,
    ) {
        if ($this->isClosed->getValue()) {
            if (! is_null($this->startTime) || ! is_null($this->endTime)) {
                throw new DomainException('休診の場合は、開始時刻と終了時刻を指定してはいけません。');
            }
        } else {
            if (is_null($this->startTime) || is_null($this->endTime)) {
                throw new DomainException('休診ではない場合は、開始時刻と終了時刻を入力してください。');
            }

            // 開始 < 終了 チェック
            if ($this->startTime->getValue() > $this->endTime->getValue()) {
                throw new DomainException('開始時刻は終了時刻より前にしてください。');
            }
        }
    }

    public static function newWithoutId(
        HospitalId $hospitalId,
        Date $date,
        ?StartTime $startTime,
        ?EndTime $endTime,
        IsClosed $isClosed,
        ?Reason $reason,
    ): self {
        return new self(
            exceptionHourId: null,
            hospitalId: $hospitalId,
            date: $date,
            startTime: $startTime,
            endTime: $endTime,
            isClosed: $isClosed,
            reason: $reason,
        );
    }

    public static function fromDatabase(
        ExceptionHourId $exceptionHourId,
        HospitalId $hospitalId,
        Date $date,
        ?StartTime $startTime,
        ?EndTime $endTime,
        IsClosed $isClosed,
        ?Reason $reason,
    ): self {
        return new self(
            exceptionHourId: $exceptionHourId,
            hospitalId: $hospitalId,
            date: $date,
            startTime: $startTime,
            endTime: $endTime,
            isClosed: $isClosed,
            reason: $reason,
        );
    }

    public function getExceptionHourId(): ExceptionHourId
    {
        if (is_null($this->exceptionHourId)) {
            throw new DomainException('保存前の例外営業時間にはIDはありません。');
        }

        return $this->exceptionHourId;
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

    public function hasBusinessHours(): bool
    {
        return ! $this->isClosed->getValue();
    }
}
