<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Menu;

use App\Application\Service\MenuService;

class UpdateMenuUseCase
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {
    }

    public function update(string $uuid, string $name, string $detail, int $requiredTime, bool $isPublished):bool
    {
        return $this->menuService->update($uuid, $name, $detail, $requiredTime, $isPublished);
    }
}
