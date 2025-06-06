<?php

declare(strict_types=1);

namespace App\Domains\Review\Repository;

use App\Models\Review;

interface ReviewRepositoryInterface
{
    public function getByUuidInHospital(int $hospitalId, string $reviewUuid): Review;
}
