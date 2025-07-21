<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['hospital'];

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
}
