<?php

namespace App\Application\Service\Hospital\Tag;

use App\Application\Service\Auth\AuthActorService;

class SyncTagService
{

    public function __construct(
        private AuthActorService $actorService,
    )
    {
    }

    public function execute(array $ids): array
    {
        $authHospital = $this->actorService->getHospital();

        return $authHospital->tags()->sync($ids);
    }
}
