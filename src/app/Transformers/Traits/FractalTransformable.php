<?php

namespace App\Transformers\Traits;

use Illuminate\Support\Collection;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

trait FractalTransformable
{
    public function transformCollection(Collection $items, TransformerAbstract $transformer)
    {
        return fractal()
            ->collection($items, $transformer)
            ->serializeWith(new ArraySerializer())
            ->toArray()['data'] ?? []; // デフォルトではdataキーでラップされている
    }
}
