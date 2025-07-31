<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Tag;

use App\Application\QueryService\TagQueryService;
use App\Application\Service\Auth\AuthActorService;
use Illuminate\Support\Collection;

class GetOwnTagsService
{
    public function __construct(
        private AuthActorService $actorService,
        private TagQueryService $tagQueryService,
    ) {
    }

    public function execute(): Collection
    {
        return $this->tagQueryService->getTagsWithSelectionByHospitalId(
            $this->actorService->getHospitalId(),
        );
    }
}
