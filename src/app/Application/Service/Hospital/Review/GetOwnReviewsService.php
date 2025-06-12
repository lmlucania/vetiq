<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Review;

use App\Application\Service\Auth\AuthActorService;
use App\Infrastructure\QueryService\ReviewQueryServiceInterface;

class GetOwnReviewsService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ReviewQueryServiceInterface $reviewQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $rating, array $sort, $queryParam)
    {
        return $this->reviewQueryService->listByHospitalId(
            hospitalId: $this->authActorService->getHospitalId(),
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            rating: $rating,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
