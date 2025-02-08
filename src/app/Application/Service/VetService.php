<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Vet\Entity\Vet;
use App\Domains\Vet\Factory\VetFactory;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Domains\Vet\ValueObjects\VetUuid;
use App\Exceptions\NotFoundException;
use App\Models\VetModel;
use Illuminate\Support\Str;

class VetService
{
    public function __construct(
        private readonly VetFactory $vetFactory,
        private readonly AuthStaffService $authStaffService,
        private readonly VetRepositoryInterface $vetRepository,
    ) {
    }

    /**
     * ログインスタッフが所属する病院の獣医師をuuidで取得する
     * @param string $uuid
     * @return Vet
     * @throws NotFoundException
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function getHospitalOwnByUuid(string $uuid) :Vet
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $vetModel  = $this->vetRepository->getByUuid(new VetUuid($uuid));
        $vetEntity = $this->vetFactory->modelToEntity($vetModel);

        if (! $vetEntity->belongsToHospital($hospitalId)) {
            throw new NotFoundException();
        }

        return $vetEntity;
    }

    public function store(string $lastName, string $firstName, bool $acceptAppointment, string $remark): bool
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $id        = $this->vetRepository->generateId(VetModel::class);
        $vetEntity = $this->vetFactory->createEntityFromPrimitive(
            id:$id,
            uuid:(string)Str::uuid(),
            hospitalId: $hospitalId->getValue(),
            lastName: $lastName,
            firstName: $firstName,
            acceptAppointment: $acceptAppointment,
            remark: $remark,
        );

        return $this->vetRepository->create($vetEntity);
    }

    public function update(string $uuid, string $lastName, string $firstName, bool $acceptAppointment, string $remark):bool
    {
        $vetEntity = $this->getHospitalOwnByUuid($uuid);

        $vetEntity = $vetEntity->update(
            lastName: $lastName,
            firstName: $firstName,
            acceptAppointment: $acceptAppointment,
            remark: $remark,
        );

        return $this->vetRepository->update($vetEntity);
    }

    public function delete(string $uuid): bool
    {
        $vetEntity   = $this->getHospitalOwnByUuid($uuid);
        $deletableId = $vetEntity->getIdForDelete();

        return $this->vetRepository->delete($deletableId);
    }
}
