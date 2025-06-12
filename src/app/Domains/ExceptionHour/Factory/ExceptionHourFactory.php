<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Factory;

use App\Application\Dto\Request\ExceptionHourDto;
use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Domains\ExceptionHour\ValueObjects\Date;
use App\Domains\ExceptionHour\ValueObjects\EndTime;
use App\Domains\ExceptionHour\ValueObjects\IsClosed;
use App\Domains\ExceptionHour\ValueObjects\Reason;
use App\Domains\ExceptionHour\ValueObjects\StartTime;
use App\Domains\Hospital\Repositories\ValueObject\HospitalId;
use App\Domains\Schedule\Enum\TimePeriod;

class ExceptionHourFactory
{
    public function dtoToInsertRows(ExceptionHourDto $dto, int $hospitalId): array
    {
        $entities = $this->dtoToEntities($dto, $hospitalId);

        return array_map(fn (ExceptionHour $entity) => $this->entityToUpsertRow($entity), $entities);
    }

    private function dtoToEntities(ExceptionHourDto $dto, int $hospitalId): array
    {
        return array_map(
            fn ($periodDto) => ExceptionHour::newWithoutId(
                hospitalId: new HospitalId($hospitalId),
                date: new Date($dto->getDate()),
                timePeriod: TimePeriod::tryFrom($periodDto->getTimePeriod()),
                startTime: $periodDto->getStartTime() ? new StartTime($periodDto->getStartTime()) : null,
                endTime:  $periodDto->getEndTime() ? new EndTime($periodDto->getEndTime()) : null,
                isClosed: new IsClosed($periodDto->isClosed()),
                reason: $periodDto->getReason() ? new Reason($periodDto->getReason()) : null,
            ),
            $dto->getPeriods(),
        );
    }

    private function entityToUpsertRow(ExceptionHour $entity): array
    {
        return [
            'hospital_id' => $entity->getHospitalId()->getValue(),
            'date'        => $entity->getDate()->getValue(),
            'time_period' => $entity->getTimePeriod()->value,
            'start_time'  => $entity->getStartTime()?->getValue(),
            'end_time'    => $entity->getEndTime()?->getValue(),
            'is_closed'   => $entity->getIsClosed()->getValue(),
            'reason'      => $entity->getReason()?->getValue(),
        ];
    }
}
