<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Review;
use App\Models\Tag;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    public function transform(\stdClass $tag)
    {
        return [
            'id' => $tag->tag_id,
            'tag_name' => $tag->tag_name,
            'display_order' => $tag->tag_display_order,
            'is_selected' => $tag->is_selected,
        ];
    }
}
