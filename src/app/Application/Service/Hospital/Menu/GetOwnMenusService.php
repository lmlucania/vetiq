<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Menu;

use App\Application\Service\Auth\AuthActorService;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetOwnMenusService
{
    public function __construct(
        private AuthActorService $authActorService,
        private MenuQueryServiceInterface $menuQueryService,
    ) {
    }

    public function execute(
        int $page,
        int $perPage,
        string $keyword,
        array $sort,
        array $queryParam
    ): LengthAwarePaginator {
        return $this->menuQueryService->listByCriteria(
            hospitalId: $this->authActorService->getHospitalId(),
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
