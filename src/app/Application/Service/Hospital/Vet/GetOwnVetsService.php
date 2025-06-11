<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Vet;

use App\Application\QueryService\VetQueryService;
use App\Application\Service\Auth\AuthActorService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetOwnVetsService
{
    public function __construct(
        private AuthActorService $authActorService,
        private VetQueryService $vetQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $sort, array $queryParam): LengthAwarePaginator
    {
        return $this->vetQueryService->listByCriteria(
            hospitalId: $this->authActorService->getHospitalId(),
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
