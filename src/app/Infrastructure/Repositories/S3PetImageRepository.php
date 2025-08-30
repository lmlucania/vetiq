<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Pet\Repository\PetImageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class S3PetImageRepository implements PetImageRepositoryInterface
{
    public function save(int $petId, UploadedFile $image): string
    {
        $path = Storage::disk('s3')->putFile("pets/{$petId}", $image);

        if ($path === false) {
            throw new \RuntimeException('S3へのアップロードに失敗しました');
        }

        return $path;
    }
}
