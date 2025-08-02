<?php

declare(strict_types=1);

namespace App\Application\Service\User\HospitalViewHistory;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\HospitalViewHistory\Repositories\HospitalViewHistoryRepositoryInterface;

class CreateHospitalViewHistoryService
{
    public function __construct(
        private AuthActorService $actorService,
        private HospitalRepositoryInterface $hospitalRepository,
        private HospitalViewHistoryRepositoryInterface $hospitalViewHistoryRepository,
    ) {
    }

    public function execute(int $hospitalId): int
    {
        // 存在チェックをする
        $existHospital = $this->hospitalRepository->getById($hospitalId);

        return $this->hospitalViewHistoryRepository->upsert(
            hospitalId: $existHospital->id,
            userId: $this->actorService->getUserId(),
        );
    }
}
