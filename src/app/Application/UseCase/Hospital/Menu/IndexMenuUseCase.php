<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Menu;

use App\Application\Dto\Response\PaginatedDto;
use App\Application\QueryService\MenuQueryService;
use App\Domains\Menu\Factory\MenuFactory;
use App\Models\Menu;

class IndexMenuUseCase
{
    public function __construct(
        private readonly MenuQueryService $menuQueryService,
        private readonly MenuFactory $menuFactory
    ) {
    }

    public function index(int $page, int $perPage, string $keyword, array $sort, $queryParam):PaginatedDto
    {
        $paginated = $this->menuQueryService->listByCriteria($page, $perPage, $keyword, $sort, $queryParam);
        $dtoList   = $paginated->map(function (Menu $menuModel) {
            $menuEntity = $this->menuFactory->modelToEntity($menuModel);
            return $this->menuFactory->entityToDto($menuEntity);
        });

        return new PaginatedDto($dtoList, $paginated);
    }
}
