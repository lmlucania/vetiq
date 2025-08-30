<?php

declare(strict_types=1);

namespace App\Application\Service\User\Pet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetImageRepositoryInterface;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class UpdatePetService
{
    public function __construct(
        private PetRepositoryInterface $petRepository,
        private AuthActorService $authActorService,
        private PetImageRepositoryInterface $petImageRepository,
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
        $useId = $this->authActorService->getUserId();
        $pet   = $this->petRepository->getByUserIdAndId($useId, $id);

        $result = $this->petRepository->update(
            id: $pet->id,
            name: $name,
            gender: $gender,
            birthday: $birthday,
            startedCareAt: $startedCareAt,
            remark: $remark,
        );

        if (empty($image)) {
            // 画像がアップロードされなくても削除はしない
            return $result;
        }

        $newPath = $this->petImageRepository->save($pet->id, $image);
        $this->petRepository->updateImagePath($pet->id, $newPath);
        $this->petImageRepository->deleteExcept($pet->id, $newPath);

        return $result;
    }
}
