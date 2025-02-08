<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Vet\Entity\Vet;
use App\Domains\Vet\Factory\VetFactory;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Domains\Vet\ValueObjects\DeletableVetId;
use App\Domains\Vet\ValueObjects\VetUuid;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\VetModel;

class VetRepository implements VetRepositoryInterface
{
    use GenerationId;

    public function __construct(
        private readonly VetFactory $vetFactory,
    ) {
    }

    public function getByUuid(VetUuid $uuid): VetModel
    {
        return VetModel::where('uuid', $uuid->getValue())->firstOrFail();
    }

    public function create(Vet $vetEntity): bool
    {
        $vetModel = $this->vetFactory->entityToModel($vetEntity);
        return $vetModel->save();
    }

    public function update(Vet $vetEntity): bool
    {
        $vetModel = VetModel::findOrFail($vetEntity->getId()->getValue());

        $vetModel->last_name          = $vetEntity->getLastName()->getValue();
        $vetModel->first_name         = $vetEntity->getFirstName()->getValue();
        $vetModel->accept_appointment = $vetEntity->getAcceptAppointment()->getValue();
        $vetModel->remark             = $vetEntity->getRemark()->getValue();

        return $vetModel->update();
    }

    public function delete(DeletableVetId $id): bool
    {
        $vetModel = VetModel::findOrFail($id->getValue());

        return $vetModel->delete();
    }
}
