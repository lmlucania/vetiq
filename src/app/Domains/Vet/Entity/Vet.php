<?php

declare(strict_types=1);

namespace App\Domains\Vet\Entity;

use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Vet\ValueObjects\AcceptAppointment;
use App\Domains\Vet\ValueObjects\FirstName;
use App\Domains\Vet\ValueObjects\LastName;
use App\Domains\Vet\ValueObjects\Remark;
use App\Domains\Vet\ValueObjects\VetId;
use App\Domains\Vet\ValueObjects\VetUuid;

class Vet
{
    public function __construct(
        private VetId $vetId,
        private VetUuid $vetUuid,
        private HospitalId $hospitalId,
        private LastName $lastName,
        private FirstName $firstName,
        private AcceptAppointment $acceptAppointment,
        private Remark $remark,
    ) {
    }

    public function getId(): VetId
    {
        return $this->vetId;
    }

    public function getUuid(): VetUuid
    {
        return $this->vetUuid;
    }

    public function getHospitalId(): HospitalId
    {
        return $this->hospitalId;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getAcceptAppointment(): AcceptAppointment
    {
        return $this->acceptAppointment;
    }

    public function getRemark(): Remark
    {
        return $this->remark;
    }
}
