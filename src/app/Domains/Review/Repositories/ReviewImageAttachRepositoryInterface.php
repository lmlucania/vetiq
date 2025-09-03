<?php

declare(strict_types=1);

namespace App\Domains\Review\Repositories;

interface ReviewImageAttachRepositoryInterface
{
    public function attachMany(int $reviewId, array $insertRows): bool;
}
