<?php

declare(strict_types=1);

namespace App\Application\Service\User\Favorite;

use App\Application\Service\Auth\AuthActorService;
use App\Infrastructure\QueryService\FavoriteQueryServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetMyFavoriteHospitalsService
{
    public function __construct(
        private AuthActorService $authActorService,
        private FavoriteQueryServiceInterface $favoriteQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        return $this->favoriteQueryService->listByCriteria(
            userId: $this->authActorService->getUserId(),
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            sort: $sort,
            queryParam: $queryParam,
        );
    }
}
