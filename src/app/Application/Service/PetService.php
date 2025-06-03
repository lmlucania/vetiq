<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

    public function create(
        string $name,
        int $gender,
        ?string $birthday,
        ?string $startedCareAt,
        ?string $remark
    ): Pet {
        $birthdayCarbon      = $birthday ? Carbon::parse($birthday) : null;
        $startedCareAtCarbon = $startedCareAt ? Carbon::parse($startedCareAt) : null;

        return $this->petRepository->create(
            uuid: (string)Str::uuid(),
            userId: $this->authActorService->getUserId(),
            name: $name,
            gender: Gender::fromInt($gender),
            birthday: $birthdayCarbon,
            startedCareAt: $startedCareAtCarbon,
            remark: $remark,
        );
    }

    public function update(
        string $uuid,
        string $name,
        int $gender,
        ?string $birthday,
        ?string $startedCareAt,
        ?string $remark
    ): bool {
        $pet                 = $this->getByUuid($uuid);
        $birthdayCarbon      = $birthday ? Carbon::parse($birthday) : null;
        $startedCareAtCarbon = $startedCareAt ? Carbon::parse($startedCareAt) : null;

        return $this->petRepository->update(
            id: $pet->id,
            name: $name,
            gender: Gender::fromInt($gender),
            birthday: $birthdayCarbon,
            startedCareAt: $startedCareAtCarbon,
            remark: $remark,
        );
    }

    public function delete(string $uuid): bool
    {
        $pet = $this->getByUuid($uuid);

        // fixme 未受診の予約がある場合は削除できない

        return $this->petRepository->delete($pet->id);
    }
}
