<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Menu;

use App\Application\Dto\Response\PaginatedDto;
use App\Application\QueryService\VetQueryService;
use App\Domains\Vet\Factory\VetFactory;
use App\Models\VetModel;

class IndexVetUseCase
{
    public function __construct(
        private readonly VetQueryService $vetQueryService,
        private readonly VetFactory $vetFactory,
    ) {
    }

    public function execute(int $page, int $perPage, string $keyword, array $sort, array $queryParam):PaginatedDto
    {
        $paginated = $this->vetQueryService->listByCriteria($page, $perPage, $keyword, $sort, $queryParam);
        $dtoList   = $paginated->map(function (VetModel $vetModel) {
            $vetEntity = $this->vetFactory->modelToEntity($vetModel);
            return $this->vetFactory->entityToDto($vetEntity);
        });

        return new PaginatedDto($dtoList, $paginated);
    }
}
