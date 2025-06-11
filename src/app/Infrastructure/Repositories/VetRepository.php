<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Models\Vet;

class VetRepository implements VetRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Vet
    {
        return Vet::where('hospital_id', $hospitalId)->findOrFail($id);
    }

    public function create(
        string $uuid,
        int $hospitalId,
        string $lastName,
        string $firstName,
        bool $acceptAppointment,
        string $remark,
    ): Vet {
        return Vet::create([
            'uuid'               => $uuid,
            'hospital_id'        => $hospitalId,
            'first_name'         => $firstName,
            'last_name'          => $lastName,
            'accept_appointment' => $acceptAppointment,
            'remark'             => $remark,
        ]);
    }

    public function update(
        int $id,
        int $hospitalId,
        string $lastName,
        string $firstName,
        bool $acceptAppointment,
        string $remark,
    ): bool {
        $vet = Vet::findOrFail($id);

        $vet->first_name         = $firstName;
        $vet->last_name          = $lastName;
        $vet->accept_appointment = $acceptAppointment;
        $vet->remark             = $remark;

        return $vet->save();
    }

    public function delete(int $id): bool
    {
        $vet = Vet::findOrFail($id);

        return $vet->delete();
    }

    public function countByHospitalId(int $hospitalId): int
    {
        return Vet::where('hospital_id', $hospitalId)->count();
    }
}
