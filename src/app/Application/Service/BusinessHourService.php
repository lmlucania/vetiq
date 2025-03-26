<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Dto\Request\BusinessHourDto;
use App\Application\QueryService\BusinessHourQueryService;
use App\Domains\BusinessHour\Entity\BusinessHour;
use App\Domains\BusinessHour\Enum\DayOfWeek;
use App\Domains\BusinessHour\Enum\TimePeriod;
use App\Domains\BusinessHour\Factory\BusinessHourFactory;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use App\Domains\BusinessHour\ValueObjects\BusinessHourUuid;
use App\Domains\BusinessHour\ValueObjects\EndTime;
use App\Domains\BusinessHour\ValueObjects\StartTime;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\NotFoundException;
use App\Models\BusinessHourModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BusinessHourService
{
    public function __construct(
        private readonly BusinessHourRepositoryInterface $businessHourRepository,
        private readonly BusinessHourQueryService $businessHourQueryService,
        private readonly BusinessHourFactory $businessHourFactory,
        private readonly AuthStaffService $authStaffService,
    ) {
    }

    public function getByUuid(string $uuid): BusinessHour
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $businessHourModel  = $this->businessHourRepository->getByUuid(new BusinessHourUuid($uuid));
        $businessHourEntity = $this->businessHourFactory->modelToEntity($businessHourModel);

        if (! $businessHourEntity->belongsToHospital($hospitalId)) {
            throw new NotFoundException();
        }

        return $businessHourEntity;
    }

    public function getList(): Collection
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $businessHourModels = $this->businessHourRepository->getListByHospitalId($hospitalId);

        return $businessHourModels->map(function (BusinessHourModel $model) {
            return $this->businessHourFactory->modelToEntity($model);
        });
    }

    public function sync(BusinessHourDto $businessHourDto): bool
    {
        $hospitalId     = $this->authStaffService->getHospitalId();
        $dayOfWeek      = $businessHourDto->getDayOfWeek();
        $periodDtoArray = $businessHourDto->getPeriods();
        $isSuccess      = true;

        // 指定した曜日において、指定された時間帯以外のデータを削除する
        $isSuccess = $isSuccess && $this->deleteByDayOfWeekExcludingTimePeriods(
            hospitalId: $hospitalId,
            dayOfWeek: $dayOfWeek,
            periodDtoArray: $periodDtoArray,
        );

        foreach ($periodDtoArray as $periodDto) {
            $entity = $this->findBySchedule(
                dayOfWeek: $dayOfWeek,
                timePeriod: $periodDto->getTimePeriod(),
            );

            $result = ($entity !== null)
                ? $this->update(
                    businessHour: $entity,
                    startTime: $periodDto->getStartTime(),
                    endTime: $periodDto->getEndTime(),
                )
                : $this->create(
                    hospitalId: $hospitalId,
                    dayOfWeek: $dayOfWeek,
                    timePeriod: $periodDto->getTimePeriod(),
                    startTime: $periodDto->getStartTime(),
                    endTime: $periodDto->getEndTime(),
                );

            // 1回でも失敗した場合はfalseを返す
            $isSuccess = $isSuccess && $result;
        }

        return $isSuccess;
    }

    public function delete(string $uuid):bool
    {
        $entity      = $this->getByUuid($uuid);
        $deletableId = $entity->getDeletableId();

        return $this->businessHourRepository->delete($deletableId);
    }

    private function findBySchedule(DayOfWeek $dayOfWeek, TimePeriod $timePeriod): ?BusinessHour
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $businessHourModel = $this->businessHourRepository->findBySchedule(
            hospitalId: $hospitalId,
            dayOfWeek: $dayOfWeek,
            timePeriod: $timePeriod,
        );

        if ($businessHourModel == null) {
            return null;
        }

        return $this->businessHourFactory->modelToEntity($businessHourModel);
    }

    private function create(
        HospitalId $hospitalId,
        DayOfWeek $dayOfWeek,
        TimePeriod $timePeriod,
        StartTime $startTime,
        EndTime $endTime
    ): bool {
        $id = $this->businessHourRepository->generateId(BusinessHourModel::class);

        $businessHour = $this->businessHourFactory->createEntityFromPrimitive(
            id:$id,
            uuid:(string)Str::uuid(),
            hospitalId: $hospitalId->getValue(),
            dayOfWeek: $dayOfWeek->value,
            timePeriod: $timePeriod->value,
            startTime: $startTime->getValue(),
            endTime: $endTime->getValue(),
        );

        return $this->businessHourRepository->create($businessHour);
    }

    private function update(BusinessHour $businessHour, StartTime $startTime, EndTime $endTime): bool
    {
        $businessHour = $businessHour->update(
            startTime: $startTime->getValue(),
            endTime: $endTime->getValue(),
        );

        return $this->businessHourRepository->update($businessHour);
    }

    /**
     * 指定した曜日において、指定された時間帯以外のデータを削除する
     * @param HospitalId $hospitalId
     * @param DayOfWeek $dayOfWeek
     * @param array $periodDtoArray
     * @return bool
     */
    private function deleteByDayOfWeekExcludingTimePeriods(
        HospitalId $hospitalId,
        DayOfWeek $dayOfWeek,
        array $periodDtoArray
    ): bool {
        $timePeriods = array_map(
            fn ($periodDto) => $periodDto->getTimePeriod(),
            $periodDtoArray,
        );

        $models = $this->businessHourQueryService->getByDayOfWeekExcludingTimePeriods(
            $hospitalId,
            $dayOfWeek,
            $timePeriods,
        );
        $isSuccess = true;

        foreach ($models as $model) {
            $entity      = $this->businessHourFactory->modelToEntity($model);
            $deletableId = $entity->getDeletableId();

            $result = $this->businessHourRepository->delete($deletableId);

            // 1回でも失敗した場合はfalseを返す
            $isSuccess = $isSuccess && $result;
        }

        return $isSuccess;
    }
}
