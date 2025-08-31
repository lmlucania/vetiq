<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Entity;

use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Domains\Appointment\ValueObjects\AppointmentAt;
use App\Domains\Appointment\ValueObjects\AppointmentId;
use App\Domains\Appointment\ValueObjects\HospitalMemo;
use App\Domains\Hospital\ValueObject\HospitalId;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Pet\ValueObjects\PetId;
use App\Domains\Vet\ValueObjects\VetId;
use App\Exceptions\DomainException;
use App\Models\StaffMember;
use Illuminate\Foundation\Auth\User;

class Appointment
{
    private function __construct(
        private readonly ?AppointmentId $id,
        private readonly PetId $petId,
        private readonly HospitalId $hospitalId,
        private readonly MenuId $menuId,
        private readonly ?VetId $vetId,
        private readonly AppointmentAt $appointmentAt,
        private readonly AppointmentStatus $status,
        private readonly ?HospitalMemo $hospitalMemo,
    ) {
    }

    public static function fromDatabase(
        AppointmentId $id,
        PetId $petId,
        HospitalId $hospitalId,
        MenuId $menuId,
        ?VetId $vetId,
        AppointmentAt $appointmentAt,
        AppointmentStatus $status,
        ?HospitalMemo $hospitalMemo,
    ): self {
        return new self(
            id: $id,
            petId: $petId,
            hospitalId: $hospitalId,
            menuId: $menuId,
            vetId: $vetId,
            appointmentAt: $appointmentAt,
            status: $status,
            hospitalMemo: $hospitalMemo,
        );
    }

    public static function newWithoutId(
        PetId $petId,
        HospitalId $hospitalId,
        MenuId $menuId,
        ?VetId $vetId,
        AppointmentAt $appointmentAt,
        AppointmentStatus $status,
        ?HospitalMemo $hospitalMemo,
    ): self {
        if ($appointmentAt->isPast()) {
            throw new DomainException('過去の日時での予約はできません。未来の日時を指定してください。');
        }

        return new self(
            id: null,
            petId: $petId,
            hospitalId: $hospitalId,
            menuId: $menuId,
            vetId: $vetId,
            appointmentAt: $appointmentAt,
            status: $status,
            hospitalMemo: $hospitalMemo,
        );
    }

    public function withId(AppointmentId $id): self
    {
        if (! is_null($this->id)) {
            throw new DomainException('すでにIDが設定された予約にはIDを再設定できません。');
        }

        return new self(
            id: $id,
            petId: $this->petId,
            hospitalId: $this->hospitalId,
            menuId: $this->menuId,
            vetId: $this->vetId,
            appointmentAt: $this->appointmentAt,
            status: $this->status,
            hospitalMemo: $this->hospitalMemo,
        );
    }

    public function getAppointmentId(): ?AppointmentId
    {
        if (is_null($this->id)) {
            throw new DomainException('保存前の予約にはIDはありません。');
        }
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
            throw new DomainException('予約が新規の状態ではないため、変更できません。');
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
