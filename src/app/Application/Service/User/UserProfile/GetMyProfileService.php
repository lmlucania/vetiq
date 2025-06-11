<?php

declare(strict_types=1);

namespace App\Application\Service\User\UserProfile;

use App\Application\QueryService\UserQueryService;
use App\Application\Service\Auth\AuthActorService;
use stdClass;

class GetMyProfileService
{
    public function __construct(
        private AuthActorService $authActorService,
        private UserQueryService $userQueryService,
    ) {
    }

    public function execute(): ?stdClass
    {
        $userId = $this->authActorService->getUserId();

        return $this->userQueryService->getById($userId);
    }
}
