<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Pet\Repository\PetImageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class S3PetImageRepository implements PetImageRepositoryInterface
{
    public function save(int $petId, UploadedFile $image): string
    {
        $path = Storage::disk('s3')->putFile($this->getDirectoryPath($petId), $image);

        if ($path === false) {
            throw new RuntimeException('S3へのアップロードに失敗しました');
        }

        return $path;
    }

    public function deleteExcept(int $petId, string $keepPath): void
    {
        $allFiles = Storage::disk('s3')->allFiles($this->getDirectoryPath($petId));

        if (empty($allFiles)) {
            return;
        }

        $toDelete = array_diff($allFiles, [$keepPath]);

        if (! empty($toDelete)) {
            Storage::disk('s3')->delete($toDelete);
        }
    }

    private function getDirectoryPath(int $petId): string
    {
        return "pets/{$petId}";
    }
}
