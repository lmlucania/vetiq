<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Review\Enum\Rating;
use App\Domains\Review\Repositories\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Review;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Review
    {
        return Review::where('id', $id)
            ->where('hospital_id', $hospitalId)
            ->firstOrFail();
    }

    public function getByHospitalIdAndIdWithImagesAndHospital(int $hospitalId, int $id): Review
    {
        return Review::with(['hospital', 'images'])
            ->where('id', $id)
            ->where('hospital_id', $hospitalId)
            ->firstOrFail();
    }

    public function create(
        int $hospitalId,
        int $userId,
        Rating $rating,
        string $title,
        ?string $body
    ): Review {
        return Review::create([
            'hospital_id' => $hospitalId,
            'user_id'     => $userId,
            'rating'      => $rating->value,
            'title'       => $title,
            'body'        => $body,
        ]);
    }

    public function update(int $id, Rating $rating, string $title, ?string $body): bool
    {
        $model = Review::findOrFail($id);

        $model->rating = $rating;
        $model->title  = $title;
        $model->body   = $body;

        return $model->save();
    }
}
