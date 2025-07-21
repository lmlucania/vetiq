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

    public function execute(int $hospitalId, int $page, int $perPage, string $keyword, array $rating, array $sort, $queryParam)
    {
        return $this->reviewQueryService->listByHospitalId(
            hospitalId: $hospitalId,
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            rating: $rating,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
