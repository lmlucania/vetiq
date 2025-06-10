<?php

declare(strict_types=1);

namespace App\Application\Service\Menu;

use App\Application\Service\AuthActorService;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListHospitalMenusService
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
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->menuQueryService->listByCriteria(
            hospitalId: $hospitalId,
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
