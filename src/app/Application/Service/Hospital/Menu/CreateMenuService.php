<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Menu;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Models\Menu;

class CreateMenuService
{
    public function __construct(
        private AuthActorService $authActorService,
        private MenuRepositoryInterface $menuRepository,
    ) {
    }

    public function execute(string $name, string $detail, int $requiredTime, bool $isPublished): Menu
    {
        $hospitalId = $this->authActorService->getHospitalId();

        return $this->menuRepository->create(
            hospitalId: $hospitalId,
            name: $name,
            detail: $detail,
            requiredTime: $requiredTime,
            isPublished: $isPublished,
        );
    }
}
