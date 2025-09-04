<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Review\Repositories\ReviewImageAttachRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReviewImageAttachRepository implements ReviewImageAttachRepositoryInterface
{
    public function attachMany(int $reviewId, array $insertRows): bool
    {
        // insert内で空配列のチェックをしているため、呼び出し元ではチェックしない
        return DB::table('review_images')->insert($insertRows);
    }

    public function detachMany(array $reviewIds): int
    {
        return DB::table('review_images')
            ->whereIn('id', $reviewIds)
            ->delete();
    }

    public function getPathsByIds(array $reviewIds): array
    {
        return DB::table('hospital_images')
            ->whereIn('id', $reviewIds)
            ->pluck('image_path')
            ->toArray();
    }

    public function getPathsByExceptIds(array $reviewIds): array
    {
        return DB::table('review_images')
            ->whereNotIn('id', $reviewIds)
            ->pluck('image_path')
            ->toArray();
    }

    public function getIdsByExceptIds(array $reviewIds): array
    {
        return DB::table('review_images')
            ->whereNotIn('id', $reviewIds)
            ->pluck('id')
            ->toArray();
    }

    public function updateDisplayOrderById(int $id, int $displayOrder): int
    {
        return DB::table('review_images')
            ->where('id', $id)
            ->update(['display_order' => $displayOrder]);
    }
}
