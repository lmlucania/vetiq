<?php

declare(strict_types=1);

namespace App\Domains\Vet\Factory;

use App\Application\Dto\Response\VetDto;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Vet\Entity\Vet;
use App\Domains\Vet\ValueObjects\AcceptAppointment;
use App\Domains\Vet\ValueObjects\FirstName;
use App\Domains\Vet\ValueObjects\LastName;
use App\Domains\Vet\ValueObjects\Remark;
use App\Domains\Vet\ValueObjects\VetId;
use App\Domains\Vet\ValueObjects\VetUuid;
use App\Models\VetModel;

class VetFactory
{
    public function modelToEntity(VetModel $vetModel): Vet
    {
        return new Vet(
            new VetId($vetModel->id),
            new VetUuid($vetModel->uuid),
            new HospitalId($vetModel->hospital_id),
            new LastName($vetModel->last_name),
            new FirstName($vetModel->first_name),
            new AcceptAppointment($vetModel->accept_appointment),
            new Remark($vetModel->remark),
        );
    }

    public function entityToModel(Vet $vetEntity):VetModel
    {
        $vetModel = new VetModel();

        $vetModel->id                 = $vetEntity->getId()->getValue();
        $vetModel->uuid               = $vetEntity->getUuid()->getValue();
        $vetModel->hospital_id        = $vetEntity->getHospitalId()->getValue();
        $vetModel->last_name          = $vetEntity->getLastName()->getValue();
        $vetModel->first_name         = $vetEntity->getFirstName()->getValue();
        $vetModel->accept_appointment = $vetEntity->getAcceptAppointment()->getValue();
        $vetModel->remark             = $vetEntity->getRemark()->getValue();

        return $vetModel;
    }

    public function entityToDto(Vet $vetEntity):VetDto
    {
        return new VetDto(
            $vetEntity->getUuid(),
            $vetEntity->getLastName(),
            $vetEntity->getFirstName(),
            $vetEntity->getAcceptAppointment(),
            $vetEntity->getRemark(),
        );
    }

    public function createEntityFromPrimitive(
        int $id,
        string $uuid,
        int $hospitalId,
        string $lastName,
        string $firstName,
        bool $acceptAppointment,
        string $remark,
    ):Vet {
        return new Vet(
            new VetId($id),
            new VetUuid($uuid),
            new HospitalId($hospitalId),
            new LastName($lastName),
            new FirstName($firstName),
            new AcceptAppointment($acceptAppointment),
            new Remark($remark),
        );
    }
}
