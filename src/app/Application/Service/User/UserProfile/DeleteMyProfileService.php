<?php

declare(strict_types=1);

namespace App\Application\Service\User\UserProfile;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\User\Repository\UserRepositoryInterface;

class DeleteMyProfileService
{
    public function __construct(
        private AuthActorService $authActorService,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function execute()
    {
        $userId = $this->authActorService->getUserId();

        // fixme 未受診の予約がある場合は削除させない

        return $this->userRepository->delete($userId);
    }
}
