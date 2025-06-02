<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Menu;

use App\Application\Service\MenuService;

class UnpublishMenuUseCase
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {
    }

    public function unpublish(string $uuid):bool
    {
        return $this->menuService->unpublish($uuid);
    }
}
