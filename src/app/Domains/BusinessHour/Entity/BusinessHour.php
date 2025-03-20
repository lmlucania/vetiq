<?php

declare(strict_types=1);

namespace App\Domains\BusinessHour\Entity;

use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Domains\BusinessHour\ValueObjects\BusinessHourId;
use App\Domains\BusinessHour\ValueObjects\BusinessHourUuid;
use App\Domains\BusinessHour\ValueObjects\DeletableBusinessHourId;
use App\Domains\BusinessHour\ValueObjects\EndTime;
use App\Domains\BusinessHour\ValueObjects\StartTime;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\DomainException;
use Carbon\Carbon;

final class BusinessHour
{
    public function __construct(
        private BusinessHourId $id,
        private BusinessHourUuid $uuid,
        private HospitalId $hospitalId,
        private DayOfWeek $dayOfWeek,
        private TimePeriod $timePeriod,
        private StartTime $startTime,
        private EndTime $endTime,
    ) {
        if ($this->startTime->getValue() > $this->endTime->getValue()) {
            throw new DomainException('開始時刻＜終了時刻で指定してください。');
        }
    }

    public function getId(): BusinessHourId
    {
        return $this->id;
    }

    public function getUuid(): BusinessHourUuid
    {
        return $this->uuid;
    }

    public function getHospitalId(): HospitalId
    {
        return $this->hospitalId;
    }

    public function getDayOfWeek(): DayOfWeek
    {
        return $this->dayOfWeek;
    }

    public function getTimePeriod(): TimePeriod
    {
        return $this->timePeriod;
    }

    public function getStartTime(): StartTime
    {
        return $this->startTime;
    }

    public function getEndTime(): EndTime
    {
        return $this->endTime;
    }

    /**
     * 病院に属しているか
     * @param HospitalId $hospitalId
     * @return bool
     */
    public function belongsToHospital(HospitalId $hospitalId): bool
    {
        return $this->hospitalId == $hospitalId;
    }

    public function update(Carbon $startTime, Carbon $endTime): self
    {
        return new $this(
            id:$this->id,
            uuid:$this->uuid,
            hospitalId:$this->hospitalId,
            dayOfWeek:$this->dayOfWeek,
            timePeriod:$this->timePeriod,
            startTime:StartTime::fromCarbon($startTime),
            endTime: EndTime::fromCarbon($endTime),
        );
    }

    /**
     * 削除可能な受付時間IDを取得する
     * @return DeletableBusinessHourId
     */
    public function getDeletableId(): DeletableBusinessHourId
    {
        return DeletableBusinessHourId::fromBusinessHourId($this->id);
    }
}
