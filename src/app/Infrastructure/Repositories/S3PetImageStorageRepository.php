<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Pet\Repository\PetImageStorageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class S3PetImageStorageRepository implements PetImageStorageRepositoryInterface
{
    public function save(int $petId, UploadedFile $image): string
    {
        $path = Storage::disk('s3_private')->putFile($this->getDirectoryPath($petId), $image);

        if ($path === false) {
            throw new RuntimeException('S3へのアップロードに失敗しました');
        }

        return $path;
    }

    public function deleteExcept(int $petId, string $keepPath): void
    {
        $allFiles = Storage::disk('s3_private')->allFiles($this->getDirectoryPath($petId));

        if (empty($allFiles)) {
            return;
        }

        $toDelete = array_diff($allFiles, [$keepPath]);

        if (! empty($toDelete)) {
            Storage::disk('s3_private')->delete($toDelete);
        }
    }

    private function getDirectoryPath(int $petId): string
    {
        return "pets/{$petId}";
    }
}
