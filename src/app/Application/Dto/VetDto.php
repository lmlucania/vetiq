<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domains\Vet\ValueObjects\AcceptAppointment;
use App\Domains\Vet\ValueObjects\FirstName;
use App\Domains\Vet\ValueObjects\LastName;
use App\Domains\Vet\ValueObjects\Remark;
use App\Domains\Vet\ValueObjects\VetUuid;

class VetDto
{
    public function __construct(
        private VetUuid $vetUuid,
        private LastName $lastName,
        private FirstName $firstName,
        private AcceptAppointment $acceptAppointment,
        private Remark $remark,
    ) {
    }

    public function getUuid(): VetUuid
    {
        return $this->vetUuid;
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
