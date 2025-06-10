<?php

declare(strict_types=1);

namespace App\Application\Service\User\Hospital;

use App\Infrastructure\QueryService\HospitalQueryServiceInterface;

class ListHospitalsService
{
    public function __construct(
        private HospitalQueryServiceInterface $hospitalQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $sort, $queryParam)
    {
        return $this->hospitalQueryService->listByCriteria(
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
