<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetImageRepositoryInterface;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class CreatePetService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
        private PetImageRepositoryInterface  $petImageRepository,
    ) {
    }

    public function execute(
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark,
        ?UploadedFile $image,
    ): Pet {
        $pet = $this->petRepository->create(
            userId: $this->authActorService->getUserId(),
            name: $name,
            gender: $gender,
            birthday: $birthday,
            startedCareAt: $startedCareAt,
            remark: $remark,
        );

        if ($image){
            $path = $this->petImageRepository->save($pet->id, $image);
            $this->petRepository->updateImagePath($pet->id, $path);
        }

        return $pet;
    }
}
