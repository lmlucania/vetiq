<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Review\Repositories\ReviewImageStorageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class S3ReviewImageStorageRepository implements ReviewImageStorageRepositoryInterface
{
    private const DISK = 's3_public';

    public function save(int $reviewId, UploadedFile $image): string
    {
        $path = Storage::disk(self::DISK)->putFile($this->getDirectoryPath($reviewId), $image);

        if ($path === false) {
            throw new RuntimeException('S3へのアップロードに失敗しました');
        }

        return $path;
    }

    public function deleteMany(array $paths): bool
    {
        return Storage::disk(self::DISK)->delete($paths);
    }

    /**
     * @param int $reviewId
     * @return string
     */
    private function getDirectoryPath(int $reviewId): string
    {
        return "reviews/{$reviewId}";
    }
}
