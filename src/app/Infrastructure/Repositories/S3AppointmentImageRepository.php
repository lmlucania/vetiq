<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Repositories\AppointmentImageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class S3AppointmentImageRepository implements AppointmentImageRepositoryInterface
{
    /**
     * @param int $appointmentId
     * @param UploadedFile[] $images
     * @return array
     */
    public function saveMany(int $appointmentId, array $images): array
    {
        $dirPath = $this->getDirectoryPath($appointmentId);

        $paths = [];
        foreach ($images as $image) {
            $paths[] = $this->save($dirPath, $image);
        }

        return $paths;
    }

    /**
     * @param array $paths
     * @return bool
     */
    public function deleteMany(array $paths): bool
    {
        return Storage::disk('s3')->delete($paths);
    }

    /**
     * @param int $appointmentId
     * @return string
     */
    private function getDirectoryPath(int $appointmentId): string
    {
        return "appointments/{$appointmentId}";
    }

    /**
     * @param string $dirPath
     * @param UploadedFile $image
     * @return string
     */
    private function save(string $dirPath, UploadedFile $image)
    {
        $path = Storage::disk('s3')->putFile($dirPath, $image);

        if ($path === false) {
            throw new RuntimeException('S3へのアップロードに失敗しました');
        }

        return $path;
    }
}
