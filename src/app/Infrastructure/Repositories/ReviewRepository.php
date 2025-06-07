<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Review\Enum\Rating;
use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Review;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getByUuidInHospital(string $hospitalUuid, string $reviewUuid): Review
    {
        $model = Review::where('uuid', $reviewUuid)
            ->whereHas('hospital', fn ($query) => $query->where('uuid', $hospitalUuid))
            ->first();

        if ($model == null) {
            throw new NotFoundException();
        }

        return $model;
    }

    public function create(
        string $uuid,
        int $hospitalId,
        int $userId,
        Rating $rating,
        string $title,
        string $body
    ): Review {
        return Review::create([
            'uuid'        => $uuid,
            'hospital_id' => $hospitalId,
            'user_id'     => $userId,
            'rating'      => $rating->value,
            'title'       => $title,
            'body'        => $body,
        ]);
    }
}
