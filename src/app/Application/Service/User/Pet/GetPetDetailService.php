<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Models\Pet;

class GetPetDetailService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(string $uuid): Pet
    {
        $useId = $this->authActorService->getUserId();
        return $this->petRepository->getByUserIdAndUuid($useId, $uuid);
    }
}
