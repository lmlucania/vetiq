<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Entity;

use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Domains\Appointment\ValueObjects\AppointmentAt;
use App\Domains\Appointment\ValueObjects\AppointmentId;
use App\Domains\Appointment\ValueObjects\HospitalMemo;
use App\Domains\Hospital\Repositories\ValueObject\HospitalId;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Pet\ValueObjects\PetId;
use App\Domains\Vet\ValueObjects\VetId;
use App\Exceptions\DomainException;
use App\Models\StaffMember;
use Illuminate\Foundation\Auth\User;

class Appointment
{
    public function __construct(
        private readonly AppointmentId $id,
        private readonly PetId $petId,
        private readonly HospitalId $hospitalId,
        private readonly MenuId $menuId,
        private readonly ?VetId $vetId,
        private readonly AppointmentAt $appointmentAt,
        private readonly AppointmentStatus $status,
        private readonly ?HospitalMemo $hospitalMemo,
    ) {
    }

    public function getAppointmentId(): AppointmentId
    {
        return $this->id;
    }

    public function getPetId(): PetId
    {
        return $this->petId;
    }

    public function getHospitalId(): HospitalId
    {
        return $this->hospitalId;
    }

    public function getMenuId(): MenuId
    {
        return $this->menuId;
    }

    public function getVetId(): ?VetId
    {
        return $this->vetId;
    }

    public function getAppointmentAt(): AppointmentAt
    {
        return $this->appointmentAt;
    }

    public function getStatus(): AppointmentStatus
    {
        return $this->status;
    }

    public function getHospitalMemo(): ?HospitalMemo
    {
        return $this->hospitalMemo;
    }

    public function cancel(User $modifier, ?HospitalMemo $hospitalMemo)
    {
        if ($modifier instanceof StaffMember) {
            return new self(
                id: $this->id,
                petId: $this->petId,
                hospitalId: $this->hospitalId,
                menuId: $this->menuId,
                vetId: $this->vetId,
                appointmentAt: $this->appointmentAt,
                status: AppointmentStatus::Cancelled,
                hospitalMemo: $hospitalMemo,
            );
        }

        if ($this->status !== AppointmentStatus::Reserved) {
            throw new DomainException('予約が新規の状態でないため、変更できません。');
        }

        if ($this->appointmentAt->isPast()) {
            throw new DomainException('予約時間が過ぎているため、キャンセルできません。');
        }

        return new self(
            id: $this->id,
            petId: $this->petId,
            hospitalId: $this->hospitalId,
            menuId: $this->menuId,
            vetId: $this->vetId,
            appointmentAt: $this->appointmentAt,
            status: AppointmentStatus::Cancelled,
            hospitalMemo: null,
        );
    }
}
