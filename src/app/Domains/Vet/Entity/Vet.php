<?php

declare(strict_types=1);

namespace App\Domains\Vet\Entity;

use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Vet\ValueObjects\AcceptAppointment;
use App\Domains\Vet\ValueObjects\DeletableVetId;
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

    /**
     * 獣医師が病院に属しているか
     * @param HospitalId $hospitalId
     * @return bool
     */
    public function belongsToHospital(HospitalId $hospitalId): bool
    {
        return $this->hospitalId == $hospitalId;
    }

    public function update(string $lastName, string $firstName, bool $acceptAppointment, string $remark): Vet
    {
        return new $this(
            vetId: $this->vetId,
            vetUuid: $this->vetUuid,
            hospitalId: $this->hospitalId,
            lastName: new LastName($lastName),
            firstName: new FirstName($firstName),
            acceptAppointment: new AcceptAppointment($acceptAppointment),
            remark: new Remark($remark),
        );
    }

    /**
     * 削除可能な獣医師IDを取得する
     * @return DeletableVetId
     */
    public function getIdForDelete(): DeletableVetId
    {
        $id = $this->getId();
        return new DeletableVetId($id->getValue());
    }
}
