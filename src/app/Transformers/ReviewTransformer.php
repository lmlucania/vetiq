<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    public function transform(Review $review)
    {
        // fixme 病院のidを除く
        return [
            'uuid'     => $review->uuid,
            'title'    => $review->title,
            'body'     => $review->body,
            'hospital' => $review->hospital,
        ];
    }
}
