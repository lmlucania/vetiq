<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use Carbon\Carbon;

class UpdatePetService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(
        string $uuid,
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark
    ): bool {
        $useId = $this->authActorService->getUserId();
        $pet   = $this->petRepository->getByUserIdAndUuid($useId, $uuid);

        return $this->petRepository->update(
            id: $pet->id,
            name: $name,
            gender: $gender,
            birthday: $birthday,
            startedCareAt: $startedCareAt,
            remark: $remark,
        );
    }
}
