<?php

declare(strict_types=1);

namespace App\Application\Service\User\Favorite;

use App\Application\Service\Auth\AuthActorService;
use App\Infrastructure\QueryService\FavoriteQueryServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MyFavoriteHospitalsService
{
    public function __construct(
        private AuthActorService $authActorService,
        private FavoriteQueryServiceInterface $favoriteQueryService,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        $userId = $this->authActorService->getUserId();

        return $this->favoriteQueryService->listByCriteria($userId, $page, $perPage, $keyword, $sort, $queryParam);
    }
}
