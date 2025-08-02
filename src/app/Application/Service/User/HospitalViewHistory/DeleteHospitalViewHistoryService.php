<?php

declare(strict_types=1);

namespace App\Application\Service\User\HospitalViewHistory;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\HospitalViewHistory\Repositories\HospitalViewHistoryRepositoryInterface;

class DeleteHospitalViewHistoryService
{
    public function __construct(
        private AuthActorService $actorService,
        private HospitalViewHistoryRepositoryInterface $hospitalViewHistoryRepository,
    ) {
    }

    public function execute(int $hospitalId): bool
    {
        $viewHistory = $this->hospitalViewHistoryRepository->getByHospitalIdAndUserId(
            hospitalId: $hospitalId,
            userId: $this->actorService->getUserId(),
        );

        return $viewHistory->delete();
    }
}
