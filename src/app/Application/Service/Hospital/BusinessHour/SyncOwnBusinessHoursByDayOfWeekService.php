<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\BusinessHour;

use App\Application\Dto\Request\BusinessHourDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncOwnBusinessHoursByDayOfWeekService
{
    public function __construct(
        private readonly BusinessHourRepositoryInterface $businessHourRepository,
        private readonly AuthActorService $authActorService,
    ) {
    }

    public function execute(BusinessHourDto $businessHourDto): bool
    {
        $hospitalId = $this->authActorService->getHospitalId();
        // トランザクション範囲を最初限にするため、トランザクションの外で実行する
        $rows = $this->buildInsertRows(hospitalId: $hospitalId, dto: $businessHourDto);

        try {
            DB::transaction(function () use ($hospitalId, $businessHourDto, $rows) {
                $this->businessHourRepository->deleteByDayOfWeekInHospital(
                    hospitalId: $hospitalId,
                    dayOfWeek: $businessHourDto->getDayOfWeek(),
                );

                $this->businessHourRepository->createMany($rows);
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Business hour sync failed', ['error' => $e]);
            return false;
        }
    }

    /**
     * insert用の配列を作成する
     * @param int $hospitalId
     * @param BusinessHourDto $dto
     * @return array
     */
    private function buildInsertRows(int $hospitalId, BusinessHourDto $dto): array
    {
        $dayOfWeek = $dto->getDayOfWeek()->value;
        $now       = now();
        $rows      = [];

        foreach ($dto->getPeriods() as $periodDto) {
            $rows[] = [
                'hospital_id' => $hospitalId,
                'day_of_week' => $dayOfWeek,
                'time_period' => $periodDto->getTimePeriod()->value,
                'start_time'  => $periodDto->getStartTime(),
                'end_time'    => $periodDto->getEndTime(),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        return $rows;
    }
}
