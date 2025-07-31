<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\HospitalViewHistory\Repositories\HospitalViewHistoryRepositoryInterface;
use App\Models\HospitalViewHistory;

class HospitalViewHistoryRepository implements HospitalViewHistoryRepositoryInterface
{
    public function getById(int $id): HospitalViewHistory
    {
        return HospitalViewHistory::findOrFail($id);
    }

    public function upsert(int $hospitalId, int $userId): int
    {
        return HospitalViewHistory::upsert(
            [
                'hospital_id' => $hospitalId,
                'user_id'     => $userId,
            ],
            [
                'hospital_id', 'user_id',
            ],
        );
    }
}
