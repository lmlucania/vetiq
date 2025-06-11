<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Repository\PetRepositoryInterface;

class DeletePetService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(string $uuid): bool
    {
        $useId = $this->authActorService->getUserId();
        $pet   = $this->petRepository->getByUserIdAndUuid($useId, $uuid);

        return $this->petRepository->delete($pet->id);
    }
}
