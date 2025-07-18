<?php

declare(strict_types=1);

namespace App\Domains\Review\Repository;

use App\Domains\Review\Enum\Rating;
use App\Models\Review;

interface ReviewRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Review;

    public function create(
        int $hospitalId,
        int $userId,
        Rating $rating,
        string $title,
        ?string $body
    ): Review;

    public function update(
        int $id,
        Rating $rating,
        string $title,
        ?string $body
    ): bool;
}
