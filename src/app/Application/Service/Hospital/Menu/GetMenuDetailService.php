<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Menu;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Models\Menu;

class GetMenuDetailService
{
    public function __construct(
        private AuthActorService $authActorService,
        private MenuRepositoryInterface $menuRepository,
    ) {
    }

    public function execute(int $id): Menu
    {
        $hospitalId = $this->authActorService->getHospitalId();
        return        $this->menuRepository->getByHospitalIdAndId(
            hospitalId: $hospitalId,
            id: $id,
        );
    }
}
