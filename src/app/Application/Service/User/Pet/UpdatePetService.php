<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetImageStorageRepositoryInterface;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class UpdatePetService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
        private PetImageStorageRepositoryInterface $petImageStorageRepository,
    ) {
    }

    public function execute(
        int $id,
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark,
        ?UploadedFile $image,
    ): bool {
        $pet = $this->petRepository->getByUserIdAndId(
            userId: $this->authActorService->getUserId(),
            id: $id,
        );

        $imagePath = $pet->image_path;
        if (! empty($image)) {
            // 画像がアップロードされなくても削除はしない
            $imagePath = $this->petImageStorageRepository->save($pet->id, $image);
            $this->petImageStorageRepository->deleteExcept($pet->id, $imagePath);
        }

        return $this->petRepository->update(
            id: $pet->id,
            name: $name,
            gender: $gender,
            birthday: $birthday,
            startedCareAt: $startedCareAt,
            remark: $remark,
            imagePath: $imagePath,
        );
    }
}
