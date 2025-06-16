<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Menu;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Models\Menu;
use Illuminate\Support\Str;

class CreateMenuService
{
    public function __construct(
        private AuthActorService $authActorService,
        private MenuRepositoryInterface $menuRepository,
    ) {
    }

    public function execute(string $name, string $detail, int $requiredTime, bool $isPublished): Menu
    {
        return $this->menuRepository->create(
            uuid: (string)Str::uuid(),
            hospitalId: $this->authActorService->getHospitalId(),
            name: $name,
            detail: $detail,
            requiredTime: $requiredTime,
            isPublished: $isPublished,
        );
    }
}
