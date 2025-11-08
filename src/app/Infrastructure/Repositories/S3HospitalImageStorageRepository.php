<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Repositories\HospitalImageStorageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * 病院に紐づく画像を S3 に保存するリポジトリクラス
 * 画像ファイルを S3 にアップロードする責務を持つ
 * 予約と保存先のパスとの紐付けは別のクラスで行う @see HospitalImageAttachRepository
 */
class S3HospitalImageStorageRepository implements HospitalImageStorageRepositoryInterface
{
    private const DISK = 's3_public';

    public function save(int $hospitalId, UploadedFile $image): string
    {
        $path = Storage::disk(self::DISK)->putFile($this->getDirectoryPath($hospitalId), $image);

        if ($path === false) {
            throw new RuntimeException('S3へのアップロードに失敗しました');
        }

        return $path;
    }

    public function deleteExcept(int $hospitalId, string $keepPath): void
    {
        $allFiles = Storage::disk(self::DISK)->allFiles($this->getDirectoryPath($hospitalId));

        if (empty($allFiles)) {
            return;
        }

        $toDelete = array_diff($allFiles, [$keepPath]);

        if (! empty($toDelete)) {
            Storage::disk(self::DISK)->delete($toDelete);
        }
    }

    public function deleteMany(array $paths): void
    {
        Storage::disk(self::DISK)->delete($paths);
    }

    private function getDirectoryPath(int $hospitalId): string
    {
        return "hospitals/{$hospitalId}";
    }
}
