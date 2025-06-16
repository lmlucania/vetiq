<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Notification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function __construct(
        private bool $includeId,
    ) {
    }

    public function transform(Notification $model)
    {
        $data = [
            'uuid'         => $model->uuid,
            'title'        => $model->title,
            'detail'       => $model->detail,
            'is_published' => $model->is_published,
            'published_at' => $model->published_at->format('Y-m-d H:i'),
        ];

        if ($this->includeId) {
            $data = array_merge(['id' => $model->id], $data);
        }

        return $data;
    }
}
