<?php

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UpdatePetService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark
    ): Pet {
        $pet = $this->petRepository->getByUuid();

        return $this->petRepository->update(
            uuid: (string)Str::uuid(),
            userId: $this->authActorService->getUserId(),
            name: $name,
            gender: $gender,
            birthday: $birthday,
            startedCareAt: $startedCareAt,
            remark: $remark,
        );
    }
}
