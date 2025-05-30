<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Menu;

use App\Application\Service\MenuService;

class PublishMenuUseCase
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {
    }

    public function publish(string $uuid):bool
    {
        return $this->menuService->publish($uuid);
    }
}
