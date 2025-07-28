<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Tag;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Tag\Repository\TagRepositoryInterface;

class SyncTagService
{
    public function __construct(
        private AuthActorService $actorService,
        private TagRepositoryInterface $tagRepository,
    ) {
    }

    public function execute(array $ids): array
    {
        $authHospital = $this->actorService->getHospital();

        $existTags = [];
        if (! empty($ids)) {
            // 存在しないタグidを渡すとエラーになる
            $existTags = $this->tagRepository->getManyByIds($ids);
        }

        return $authHospital->tags()->sync($existTags);
    }
}
