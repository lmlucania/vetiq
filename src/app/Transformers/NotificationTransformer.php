<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Notification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(Notification $model)
    {
        return [
            'uuid'         => $model->uuid,
            'title'        => $model->title,
            'detail'       => $model->detail,
            'is_published' => $model->is_published,
            'published_at' => $model->published_at->format('H:i'),
        ];
    }
}
