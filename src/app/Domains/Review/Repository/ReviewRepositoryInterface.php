<?php

declare(strict_types=1);

namespace App\Domains\Review\Repository;

use App\Domains\Review\Enum\Rating;
use App\Models\Review;

interface ReviewRepositoryInterface
{
    public function getByHospitalUuidAndUuid(string $hospitalUuid, string $uuid): Review;

    public function create(
        string $uuid,
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
