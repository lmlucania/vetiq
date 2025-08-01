<?php

declare(strict_types=1);

namespace App\Application\Service\User\HospitalViewHistory;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\HospitalViewHistory\Repositories\HospitalViewHistoryRepositoryInterface;

class ClearHospitalViewHistoriesService
{
    public function __construct(
        private AuthActorService $actorService,
        private HospitalViewHistoryRepositoryInterface $hospitalViewHistoryRepository,
    ) {
    }

    public function execute(): int
    {
        return $this->hospitalViewHistoryRepository->deleteManyByUserId(
            userId: $this->actorService->getUserId(),
        );
    }
}
