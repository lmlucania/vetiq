<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\ExceptionHour;

use App\Application\Dto\Request\ExceptionHourDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\ExceptionHour\Factory\ExceptionHourFactory;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncOwnExceptionHoursByDateService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ExceptionHourRepositoryInterface $exceptionHourRepository,
        private ExceptionHourFactory $exceptionHourFactory,
    ) {
    }

    public function execute(ExceptionHourDto $exceptionHourDto)
    {
        $hospitalId = $this->authActorService->getHospitalId();
        // トランザクション範囲を最初限にするため、トランザクションの外で実行する
        $upsertRows = $this->exceptionHourFactory->dtoToInsertRows(dto: $exceptionHourDto, hospitalId: $hospitalId);

        try {
            DB::transaction(function () use ($hospitalId, $exceptionHourDto, $upsertRows) {
                $this->exceptionHourRepository->deleteByDateInHospital(
                    hospitalId: $hospitalId,
                    date: $exceptionHourDto->getDate(),
                );

                $this->exceptionHourRepository->createMany($upsertRows);
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Business hour sync failed', ['error' => $e]);
            return false;
        }
    }
}
