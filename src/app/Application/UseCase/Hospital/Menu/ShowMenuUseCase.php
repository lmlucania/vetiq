<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Menu;

use App\Application\Dto\Response\MenuDto;
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
