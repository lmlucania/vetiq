<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use Illuminate\Support\Collection;

class GetMyPetsService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(): Collection
    {
        $userId = $this->authActorService->getUserId();

        return $this->petRepository->getListByUserId($userId);
    }
}
