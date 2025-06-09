<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Dto\Request\BusinessHourDto;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use App\Models\BusinessHour;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BusinessHourService
{
    public function __construct(
        private readonly BusinessHourRepositoryInterface $businessHourRepository,
        private readonly AuthActorService $authActorService,
    ) {
    }

    public function getOwnById(int $id): BusinessHour
    {
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->businessHourRepository->getByIdInHospital($hospitalId, $id);
    }

    public function getListOwn(): Collection
    {
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->businessHourRepository->getListByHospitalId($hospitalId);
    }

    public function sync(BusinessHourDto $businessHourDto): bool
    {
        $hospitalId = $this->authActorService->getHospitalId();

        try {
            DB::transaction(function () use ($hospitalId, $businessHourDto) {
                $this->businessHourRepository->deleteByDayOfWeekInHospital(
                    hospitalId: $hospitalId,
                    dayOfWeek: $businessHourDto->getDayOfWeek(),
                );

                $this->businessHourRepository->createMany(
                    $this->buildInsertRows(
                        hospitalId: $hospitalId,
                        dto: $businessHourDto,
                    ),
                );
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Business hour sync failed', ['error' => $e]);
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $businessHour = $this->getOwnById($id);

        return $this->businessHourRepository->delete($businessHour->id);
    }

    /**
     * insert用の配列を作成する
     * @param int $hospitalId
     * @param BusinessHourDto $dto
     * @return array
     */
    private function buildInsertRows(int $hospitalId, BusinessHourDto $dto): array
    {
        $now  = now();
        $rows = [];

        foreach ($dto->getPeriods() as $period) {
            $rows[] = [
                'hospital_id' => $hospitalId,
                'day_of_week' => $dto->getDayOfWeek()->value,
                'time_period' => $period->getTimePeriod()->value,
                'start_time'  => $period->getStartTime(),
                'end_time'    => $period->getEndTime(),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        return $rows;
    }
}
