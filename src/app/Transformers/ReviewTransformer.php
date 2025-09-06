<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Review;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['hospital', 'images'];

    public function transform(Review $review)
    {
        return [
            'id'     => $review->id,
            'rating' => $review->rating->value,
            'title'  => $review->title,
            'body'   => $review->body,
        ];
    }

    public function includeHospital(Review $review)
    {
        return $this->item($review->hospital, new HospitalTransformer());
    }

    public function includeImages(Review $review): Collection
    {
        return $this->collection($review->images, new ImageTransformer());
    }
}
