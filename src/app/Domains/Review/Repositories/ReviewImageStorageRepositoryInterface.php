<?php

declare(strict_types=1);

namespace App\Domains\Review\Repositories;

use Illuminate\Http\UploadedFile;

interface ReviewImageStorageRepositoryInterface
{
    public function save(int $reviewId, UploadedFile $image): string;

    public function deleteMany(array $paths): bool;
}
