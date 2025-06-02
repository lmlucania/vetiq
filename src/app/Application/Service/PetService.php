<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Pet;
use Illuminate\Support\Collection;

class PetService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function getByUuid(string $uuid): Pet
    {
        $pet = $this->petRepository->getByUuid($uuid);

        if ($this->authActorService->isStaff()) {
            return $pet;
        }

        if ($pet->user_id != $this->authActorService->getUserId()) {
            throw new NotFoundException();
        }

        return $pet;
    }

    public function getMyPets(): Collection
    {
        $userId = $this->authActorService->getUserId();

        return $this->petRepository->getListByUserId($userId);
    }
}
