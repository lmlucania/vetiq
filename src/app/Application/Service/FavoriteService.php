<?php

declare(strict_types=1);

namespace App\Application\Service;

class FavoriteService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalService $hospitalService,
    ) {
    }

    public function attach(string $uuid): array
    {
        $user = $this->authActorService->getUser();

        $hospital = $this->hospitalService->getByUuid($uuid);
        return $user->favoriteHospitals()->sync([$hospital->id]);
    }

    public function detach(string $uuid): int
    {
        $user = $this->authActorService->getUser();

        $hospital = $this->hospitalService->getByUuid($uuid);
        return $user->favoriteHospitals()->detach($hospital->id);
    }
}
