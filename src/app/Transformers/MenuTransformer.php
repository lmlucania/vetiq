<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Menu;
use League\Fractal\TransformerAbstract;

class MenuTransformer extends TransformerAbstract
{
    public function transform(Menu $model)
    {
        return [
            'uuid'          => $model->uuid,
            'name'          => $model->name,
            'detail'        => $model->detail,
            'required_time' => $model->required_time,
            'is_published'  => $model->is_published,
        ];
    }
}
