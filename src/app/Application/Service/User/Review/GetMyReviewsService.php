<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Application\Service\Auth\AuthActorService;
use App\Infrastructure\QueryService\ReviewQueryServiceInterface;

class GetMyReviewsService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ReviewQueryServiceInterface $reviewQueryService,
    ) {
    }

    public function execute(
        int $page,
        int $perPage,
        string $keyword,
        array $rating,
        array $sort,
        array $queryParam
    ) {
        return $this->reviewQueryService->listByCriteriaInUser(
            userId: $this->authActorService->getUserId(),
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            rating: $rating,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
