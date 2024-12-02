<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital;

use App\Application\Service\MenuService;

class StoreMenuUseCase
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {
    }

    public function store(string $name, string $detail, int $requiredTime, bool $isPublished):bool
    {
        return $this->menuService->store($name, $detail, $requiredTime, $isPublished);
    }
}
