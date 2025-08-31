<?php

declare(strict_types=1);

namespace App\Domains\ExceptionHour\Factory;

use App\Application\Dto\Request\ExceptionHourDto;
use App\Application\Dto\Request\ExceptionHourPeriodDto;
use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Domains\ExceptionHour\ValueObjects\Date;
use App\Domains\ExceptionHour\ValueObjects\EndTime;
use App\Domains\ExceptionHour\ValueObjects\IsClosed;
use App\Domains\ExceptionHour\ValueObjects\Reason;
use App\Domains\ExceptionHour\ValueObjects\StartTime;
use App\Domains\Hospital\ValueObject\HospitalId;

class ExceptionHourFactory
{
    public function dtoToEntities(ExceptionHourDto $dto, int $hospitalId): array
    {
        return array_map(
            fn (ExceptionHourPeriodDto $periodDto) => ExceptionHour::newWithoutId(
                hospitalId: new HospitalId($hospitalId),
                date: new Date($dto->getDate()),
                startTime: $periodDto->getStartTime() ? new StartTime($periodDto->getStartTime()) : null,
                endTime:  $periodDto->getEndTime() ? new EndTime($periodDto->getEndTime()) : null,
                isClosed: new IsClosed($periodDto->isClosed()),
                reason: $periodDto->getReason() ? new Reason($periodDto->getReason()) : null,
            ),
            $dto->getPeriods(),
        );
    }

    public function entitesToInsertRows(array $entities): array
    {
        return array_map(fn (ExceptionHour $entity) => $this->entityToInsertRow($entity), $entities);
    }

    private function entityToInsertRow(ExceptionHour $entity): array
    {
        return [
            'hospital_id' => $entity->getHospitalId()->getValue(),
            'date'        => $entity->getDate()->getValue(),
            'start_time'  => $entity->getStartTime()?->getValue(),
            'end_time'    => $entity->getEndTime()?->getValue(),
            'is_closed'   => $entity->getIsClosed()->getValue(),
            'reason'      => $entity->getReason()?->getValue(),
        ];
    }
}
