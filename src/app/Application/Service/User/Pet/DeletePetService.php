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

    public function execute(int $id): bool
    {
        // 病院側から確認できるようにペット画像は削除しない
        $pet = $this->petRepository->getByUserIdAndId(
            userId: $this->authActorService->getUserId(),
            id: $id,
        );

        return $this->petRepository->delete($pet->id);
    }
}
