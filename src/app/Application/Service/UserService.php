<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\QueryService\UserQueryService;
use stdClass;

class UserService
{
    public function __construct(
        private AuthActorService $authActorService,
        private UserQueryService $userQueryService,
    ) {
    }

    public function getAuthUser(): ?stdClass
    {
        $userId = $this->authActorService->getUserId();

        return $this->userQueryService->getById($userId);
    }
}
