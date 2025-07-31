<?php

declare(strict_types=1);

namespace App\Application\Service\User\HospitalInfo;

use App\Domains\Tag\Repository\TagRepositoryInterface;
use App\Infrastructure\QueryService\HospitalQueryServiceInterface;

class GetHospitalsService
{
    public function __construct(
        private HospitalQueryServiceInterface $hospitalQueryService,
        private TagRepositoryInterface $tagRepository,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $tagIds, array $prefectureCodes, array $sort, $queryParam)
    {
        $existTags = $this->tagRepository->getManyByIds($tagIds);

        return $this->hospitalQueryService->listByCriteria(
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            tagIds: $existTags->pluck('id')->toArray(),
            prefectureCodes: $prefectureCodes,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
