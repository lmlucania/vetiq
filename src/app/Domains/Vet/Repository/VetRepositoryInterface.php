<?php

declare(strict_types=1);

namespace App\Domains\Vet\Repository;

use App\Models\Vet;

interface VetRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Vet;

    public function create(
        string $uuid,
        int $hospitalId,
        string $lastName,
        string $firstName,
        bool $acceptAppointment,
        string $remark,
    ): Vet;

    public function update(
        int $id,
        int $hospitalId,
        string $lastName,
        string $firstName,
        bool $acceptAppointment,
        string $remark,
    ):bool;

    public function delete(int $id):bool;

    public function countByHospitalId(int $hospitalId): int;
}
