<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital;

use App\Application\Dto\MenuDto;
use App\Application\Service\MenuService;
use App\Domains\Menu\Factory\MenuFactory;

class ShowMenuUseCase
{
    public function __construct(
        private readonly MenuService $menuService,
        private readonly MenuFactory $menuFactory
    ) {
    }

    public function show(string $uuid):MenuDto
    {
        $menuEntity = $this->menuService->getHospitalOwnByUuid($uuid);
        return $this->menuFactory->entityToDto($menuEntity);
    }
}
