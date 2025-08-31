<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Repositories\HospitalImageStorageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class S3HospitalImageStorageRepository implements HospitalImageStorageRepositoryInterface
{
    public function save(int $hospitalId, UploadedFile $image): string
    {
        $path = Storage::disk('s3')->putFile($this->getDirectoryPath($hospitalId), $image);

        if ($path === false) {
            throw new RuntimeException('S3へのアップロードに失敗しました');
        }

        return $path;
    }

    public function deleteExcept(int $hospitalId, string $keepPath): void
    {
        $allFiles = Storage::disk('s3')->allFiles($this->getDirectoryPath($hospitalId));

        if (empty($allFiles)) {
            return;
        }

        $toDelete = array_diff($allFiles, [$keepPath]);

        if (! empty($toDelete)) {
            Storage::disk('s3')->delete($toDelete);
        }
    }

    private function getDirectoryPath(int $hospitalId): string
    {
        return "hospitals/{$hospitalId}";
    }
}
