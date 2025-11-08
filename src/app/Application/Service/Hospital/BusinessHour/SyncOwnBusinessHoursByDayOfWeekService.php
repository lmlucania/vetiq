<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\BusinessHour;

use App\Application\Dto\Request\BusinessHourDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\BusinessHour\Repositories\BusinessHourRepositoryInterface;
use App\Exceptions\DomainException;
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
        $this->validateOverlaps($businessHourDto);

        $hospitalId = $this->authActorService->getHospitalId();
        // トランザクション範囲を最初限にするため、トランザクションの外で実行する
        $insertRows = $this->buildInsertRows(hospitalId: $hospitalId, dto: $businessHourDto);

        try {
            DB::transaction(function () use ($hospitalId, $businessHourDto, $insertRows) {
                $this->businessHourRepository->deleteByDayOfWeekInHospital(
                    hospitalId: $hospitalId,
                    dayOfWeek: $businessHourDto->getDayOfWeek(),
                );

                $this->businessHourRepository->createMany($insertRows);
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Fail to sync business hour', ['error' => $e]);
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
        $dayOfWeek = $dto->getDayOfWeek();

        return array_map(
            fn ($periodDto) => [
                'hospital_id' => $hospitalId,
                'day_of_week' => $dayOfWeek,
                'start_time'  => $periodDto->getStartTime(),
                'end_time'    => $periodDto->getEndTime(),
            ],
            $dto->getPeriods(),
        );
    }

    /**
     * 時間が重複してないことをチェックする
     * @param BusinessHourDto $dto
     * @return void
     * @throws DomainException
     */
    private function validateOverlaps(BusinessHourDto $dto): void
    {
        // start_timeとend_timeの相関チェックはStoreBusinessHourRequestで実施済み
        $periods = array_map(
            fn ($periodDto) => [
                'start_time' => $periodDto->getStartTime(),
                'end_time'   => $periodDto->getEndTime(),
            ],
            $dto->getPeriods(),
        );

        // 重複チェックをするために開始時間で昇順にする
        usort($periods, fn ($a, $b) => strcmp($a['start_time'], $b['start_time']));

        $conflicts = [];
        for ($i = 0; $i < count($periods) - 1; $i++) {
            $current = $periods[$i];
            $next    = $periods[$i + 1];

            // 現在の end_time が次の start_time より大きい場合は重複している
            if ($current['end_time'] > $next['start_time']) {
                $conflicts[] = sprintf(
                    '%s-%s と %s-%s',
                    $current['start_time'],
                    $current['end_time'],
                    $next['start_time'],
                    $next['end_time'],
                );
            }
        }

        if (! empty($conflicts)) {
            throw new DomainException(
                '営業時間が重複しています: ' . implode(', ', $conflicts),
            );
        }
    }
}
