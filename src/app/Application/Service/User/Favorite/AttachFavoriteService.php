<?php

declare(strict_types=1);

namespace App\Application\Service\User\Favorite;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;

class AttachFavoriteService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalRepositoryInterface $hospitalRepository,
    ) {
    }

    public function execute(int $id): array
    {
        $user = $this->authActorService->getUser();

        $hospital = $this->hospitalRepository->getById($id);
        // すでにお気に入り登録されていても、重複登録せずにスルーされる
        return $user->favoriteHospitals()->syncWithoutDetaching($hospital->id);
    }
}
