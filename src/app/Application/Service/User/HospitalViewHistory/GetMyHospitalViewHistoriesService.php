<?php

declare(strict_types=1);

namespace App\Application\Service\User\HospitalViewHistory;

use App\Application\Service\Auth\AuthActorService;
use App\Infrastructure\QueryService\HospitalViewHistoryQueryServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetMyHospitalViewHistoriesService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalViewHistoryQueryServiceInterface $hospitalViewHistoryQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, array $sort, array $queryParam): LengthAwarePaginator
    {
        return $this->hospitalViewHistoryQueryService->listByCriteria(
            userId: $this->authActorService->getUserId(),
            page:$page,
            perPage: $perPage,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
