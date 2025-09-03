<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Repositories;

interface HospitalImageAttachRepositoryInterface
{
    public function attachMany(int $hospitalId, array $insertRows): bool;

    public function detachMany(int $hospitalId, array $ids): int;

    public function getPathsByHospitalIdAndIds(int $hospitalId, array $ids): array;

    public function getPathsByHospitalIdExceptIds(int $hospitalId, array $ids): array;

    public function getIdsByHospitalIdExceptiIds(int $hospitalId, array $ids): array;

    public function updateDisplayOrderByHospitalIdAndId(int $hospitalId, int $id, int $displayOrder): int;
}
