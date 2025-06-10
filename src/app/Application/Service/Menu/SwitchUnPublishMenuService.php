<?php

declare(strict_types=1);

namespace App\Application\Service\Menu;

use App\Application\Service\AuthActorService;
use App\Domains\Menu\Repository\MenuRepositoryInterface;

class SwitchUnPublishMenuService
{
    public function __construct(
        private AuthActorService $authActorService,
        private MenuRepositoryInterface $menuRepository,
    ) {
    }

    public function execute(int $id)
    {
        $hospitalId = $this->authActorService->getHospitalId();
        $menu       = $this->menuRepository->getByIdAndHospital(
            hospitalId: $hospitalId,
            id: $id,
        );

        return $this->menuRepository->update(
            id: $menu->id,
            name: $menu->name,
            detail: $menu->detail,
            requiredTime: $menu->requiredTime,
            isPublished: false,
        );
    }
}
