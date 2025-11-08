<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Repositories;

use Illuminate\Http\UploadedFile;

interface HospitalImageStorageRepositoryInterface
{
    //    public function saveMany(int $hospitalId, array $images): array;

    public function save(int $hospitalId, UploadedFile $image): string;

    public function deleteExcept(int $hospitalId, string $keepPath): void;

    public function deleteMany(array $paths): void;
}
