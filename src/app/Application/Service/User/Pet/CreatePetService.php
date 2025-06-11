<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CreatePetService
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
        return $this->petRepository->create(
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
