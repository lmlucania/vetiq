<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Pet;
use App\Models\Review;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getByUuid(string $uuid): Review
    {
        $model = Review::firstWhere('uuid', $uuid);
        if ($model == null) {
            throw new NotFoundException();
        }

        return $model;
    }

}
