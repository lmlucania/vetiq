<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Menu;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Menu\Repository\MenuRepositoryInterface;

class UpdateMenuService
{
    public function __construct(
        private AuthActorService $authActorService,
        private MenuRepositoryInterface $menuRepository,
    ) {
    }

    public function execute(int $id, string $name, string $detail, int $requiredTime, bool $isPublished): bool
    {
        $hospitalId = $this->authActorService->getHospitalId();
        $menu       = $this->menuRepository->getByIdAndHospital(
            hospitalId: $hospitalId,
            id: $id,
        );

        return $this->menuRepository->update(
            id: $menu->id,
            name: $name,
            detail: $detail,
            requiredTime: $requiredTime,
            isPublished: $isPublished,
        );
    }
}
