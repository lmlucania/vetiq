<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Review;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getByUuidInHospital(int $hospitalId, string $reviewUuid): Review
    {
        $model = Review::where('hospital_id', $hospitalId)->firstWhere('uuid', $reviewUuid);
        if ($model == null) {
            throw new NotFoundException();
        }

        return $model;
    }
}
