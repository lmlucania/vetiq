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
            'id'            => $model->id,
            'name'          => $model->name,
            'detail'        => $model->detail,
            'required_time' => $model->required_time,
            'is_published'  => $model->is_published,
        ];
    }

    public static function fromJoin(
        int $menuId,
        string $name,
        string $detail,
        int $requiredTime,
        int $isPublished,
    ): array {
        return [
            'id'            => $menuId,
            'name'          => $name,
            'detail'        => $detail,
            'required_time' => $requiredTime,
            'is_published'  => (bool)$isPublished,
        ];
    }
}
