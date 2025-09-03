<?php

declare(strict_types=1);

namespace App\Domains\Appointment\Repositories;

use Illuminate\Http\UploadedFile;

interface AppointmentImageStorageRepositoryInterface
{
    public function save(int $appointmentId, UploadedFile $image): string;

    public function deleteMany(array $paths): bool;
}
