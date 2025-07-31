<?php

declare(strict_types=1);

namespace App\Domains\HospitalViewHistory\Repositories;

use App\Models\HospitalViewHistory;

interface HospitalViewHistoryRepositoryInterface
{
    public function getById(int $id): HospitalViewHistory;

    public function getByHospitalIdAndUserId(int $hospitalId, int $userId): HospitalViewHistory;

    public function upsert(int $hospitalId, int $userId): int;
}
