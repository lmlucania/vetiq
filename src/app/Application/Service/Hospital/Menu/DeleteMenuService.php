<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Menu;

nu;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Menu\Repository\MenuRepositoryInterface;

class DeleteMenuService
{
    public function __construct(
        private AuthActorService $authActorService,
        private MenuRepositoryInterface $menuRepository,
    ) {
    }

    public function execute(int $id): bool
    {
        $hospitalId = $this->authActorService->getHospitalId();
        $menu       = $this->menuRepository->getByHospitalIdAndId(
            hospitalId: $hospitalId,
            id: $id,
        );

        return $this->menuRepository->delete($menu->id);
    }
}
