<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Notification\Repository\NotificationRepositoryInterface;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Notification
    {
        return Notification::where('hospital_id', $hospitalId)->findOrFail($id);
    }

    public function create(string $uuid, int $hospitalId, string $title, string $detail, bool $isPublished, Carbon $publishedAt): Notification
    {
        return Notification::create([
            'uuid'         => $uuid,
            'hospital_id'  => $hospitalId,
            'title'        => $title,
            'detail'       => $detail,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);
    }

    public function update(int $id, string $title, string $detail, bool $isPublished, Carbon $publishedAt): bool
    {
        $notification = Notification::findOrFail($id);

        $notification->title        = $title;
        $notification->detail       = $detail;
        $notification->is_published = $isPublished;
        $notification->published_at = $publishedAt;

        return $notification->save();
    }

    public function delete(int $id): bool
    {
        return Notification::findOrFail($id)->delete();
    }
}
