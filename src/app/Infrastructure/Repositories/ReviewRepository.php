<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Review;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getByUuid(string $reviewUuid): Review
    {
        $model = Review::firstWhere('uuid', $reviewUuid);
        if ($model == null) {
            throw new NotFoundException();
        }

        return $model;
    }
}
