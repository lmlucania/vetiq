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
        return DB::table('review_image')->insert($insertRows);
    }
}
