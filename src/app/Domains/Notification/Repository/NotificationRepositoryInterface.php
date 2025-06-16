<?php

declare(strict_types=1);

namespace App\Domains\Notification\Repository;

use App\Models\Notification;
use Carbon\Carbon;

interface NotificationRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Notification;

    public function create(string $uuid, int $hospitalId, string $title, string $detail, bool $isPublished, Carbon $publishedAt): Notification;

    public function update(int $id, string $title, string $detail, bool $isPublished, Carbon $publishedAt):bool;

    public function delete(int $id):bool;
}
