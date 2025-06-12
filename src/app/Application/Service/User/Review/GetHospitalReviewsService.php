<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Infrastructure\QueryService\ReviewQueryServiceInterface;

class GetHospitalReviewsService
{
    public function __construct(
        private ReviewQueryServiceInterface $reviewQueryService,
    ) {
    }

    public function execute(string $hospitalUuid, int $page, int $perPage, string $keyword, array $rating, array $sort, $queryParam)
    {
        return $this->reviewQueryService->listByHospitalUuid(
            hospitalUuid: $hospitalUuid,
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            rating: $rating,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
