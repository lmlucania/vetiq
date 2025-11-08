<?php

declare(strict_types=1);

namespace App\Domains\Pet\Repository;

use Illuminate\Http\UploadedFile;

interface PetImageStorageRepositoryInterface
{
    public function save(int $petId, UploadedFile $image): string;

    public function deleteExcept(int $petId, string $keepPath): void;
}
