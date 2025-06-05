<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\QueryService\FavoriteQueryService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FavoriteService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalService $hospitalService,
        private FavoriteQueryService $favoriteQueryService,
    ) {
    }

    public function attach(string $uuid): array
    {
        $user = $this->authActorService->getUser();

        $hospital = $this->hospitalService->getByUuid($uuid);
        // すでにお気に入り登録されていても、重複登録せずにスルーされる
        return $user->favoriteHospitals()->syncWithoutDetaching($hospital->id);
    }

    public function detach(string $uuid): int
    {
        $user = $this->authActorService->getUser();

        $hospital = $this->hospitalService->getByUuid($uuid);
        return $user->favoriteHospitals()->detach($hospital->id);
    }

    public function myFavoriteHospitals(int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        $userId = $this->authActorService->getUserId();

        return $this->favoriteQueryService->listByCriteria($userId, $page, $perPage, $keyword, $sort, $queryParam);
    }
}
