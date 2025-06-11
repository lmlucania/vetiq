<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Favorite;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class FavoriteTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['hospital'];

    public function transform(Favorite $favorite)
    {
        return [
            'id' => $favorite->id,
        ];
    }

    public function includeHospital(Favorite $favorite): Item
    {
        return $this->item($favorite->hospital, new HospitalTransformer());
    }
}
