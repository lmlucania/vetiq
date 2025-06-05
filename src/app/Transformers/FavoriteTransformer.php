<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Favorite;
use League\Fractal\TransformerAbstract;

class FavoriteTransformer extends TransformerAbstract
{
    public function transform(Favorite $favorite)
    {
        // fixme 病院のidを除く
        return [
            'id'       => $favorite->id,
            'hospital' => $favorite->hospital,
        ];
    }
}
