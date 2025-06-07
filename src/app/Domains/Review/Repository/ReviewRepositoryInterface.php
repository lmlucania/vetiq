<?php

declare(strict_types=1);

namespace App\Domains\Review\Repository;

use App\Domains\Review\Enum\Rating;
use App\Models\Review;

interface ReviewRepositoryInterface
{
    public function getByUuidInHospital(string $hospitalUuid, string $reviewUuid): Review;

    public function create(
        string $uuid,
        int $hospitalId,
        int $userId,
        Rating $rating,
        string $title,
        string $body
    ): Review;
}
