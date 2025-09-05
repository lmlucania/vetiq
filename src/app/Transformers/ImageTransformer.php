<?php

declare(strict_types=1);

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    public function transform(Model $image)
    {
        return [
            'id'            => $image->id,
            'image_path'    => $image->image_path,
            'display_order' => $image->display_order,
        ];
    }
}
