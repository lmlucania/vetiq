<?php

declare(strict_types=1);

namespace App\Application\Service\User\HospitalInfo;

use App\Application\QueryService\TagQueryService;
use App\Domains\Tag\Repository\TagRepositoryInterface;
use App\Infrastructure\QueryService\HospitalQueryServiceInterface;

class GetHospitalsService
{
    public function __construct(
        private HospitalQueryServiceInterface $hospitalQueryService,
        private TagRepositoryInterface $tagRepository,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $tagIds, array $sort, $queryParam)
    {
        $existTags = $this->tagRepository->getManyByIds($tagIds);

        return $this->hospitalQueryService->listByCriteria(
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            tagIds: $existTags->pluck('id')->toArray(),
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
