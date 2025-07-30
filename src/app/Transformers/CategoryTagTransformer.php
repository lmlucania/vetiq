<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Tag;
use App\Transformers\Traits\FractalTransformable;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

class CategoryTagTransformer extends TransformerAbstract
{
    use FractalTransformable;

    public function transform(Collection $tags)
    {
        // tag categoryの情報は同じなので、一番目のデータから取得する
        $first = $tags->first();
        return [
            'category_name'          => $first->category_name,
            'category_display_order' => $first->category_display_order,
            'tag'                    => $this->transformCollection($tags, new TagTransformer()),
        ];
    }
}
