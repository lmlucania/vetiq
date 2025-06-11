<?php

declare(strict_types=1);

namespace App\Application\Service\User\Favorite;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;

class DetachFavoriteService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalRepositoryInterface $hospitalRepository,
    ) {
    }

    public function execute(string $uuid): int
    {
        $user = $this->authActorService->getUser();

        $hospital = $this->hospitalRepository->getByUuid($uuid);
        return $user->favoriteHospitals()->detach($hospital->id);
    }
}
