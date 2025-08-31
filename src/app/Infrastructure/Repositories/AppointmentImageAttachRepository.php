<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Repositories\AppointmentImageAttachRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 予約と保存先のパスとの紐付け、解除をするリポジトリクラス
 * 中間テーブルを操作する責務を持つ
 * 画像ファイルを S3 にアップロードは別のクラスで行う @see S3AppointmentImageStorageRepository
 */
class AppointmentImageAttachRepository implements AppointmentImageAttachRepositoryInterface
{
    public function attach(int $appointmentId, array $paths): bool
    {
        if (empty($paths)) {
            return true;
        }

        $records = [];
        foreach ($paths as $index => $path) {
            $records[] = [
                'appointment_id' => $appointmentId,
                'image_path'     => $path,
                'display_order'  => $index + 1,
            ];
        }

        try {
            DB::table('appointment_image')->insert($records);
            return true;
        } catch (Throwable $e) {
            Log::error('Failed to attach appointment images', [
                'appointment_id' => $appointmentId,
                'paths'          => $paths,
                'error'          => $e->getMessage(),
            ]);
            return false;
        }
    }
}
