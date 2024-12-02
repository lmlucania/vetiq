<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital;

use App\Application\Service\MenuService;

class DestroyMenuUseCase
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {
    }

    public function destroy(string $uuid):bool
    {
        return $this->menuService->delete($uuid);
    }
}
