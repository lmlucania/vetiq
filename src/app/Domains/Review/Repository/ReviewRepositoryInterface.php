<?php

namespace App\Domains\Review\Repository;

use App\Models\Review;

interface ReviewRepositoryInterface
{
    public function getByUuid(string $uuid): Review;
}
