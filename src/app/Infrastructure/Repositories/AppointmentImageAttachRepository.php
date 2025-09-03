<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Appointment\Repositories\AppointmentImageAttachRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * 予約と保存先のパスとの紐付け、解除をするリポジトリクラス
 * 中間テーブルを操作する責務を持つ
 * 画像ファイルを S3 にアップロードは別のクラスで行う @see S3AppointmentImageStorageRepository
 */
class AppointmentImageAttachRepository implements AppointmentImageAttachRepositoryInterface
{
    public function attachMany(int $appointmentId, array $insertRows): bool
    {
        // insert内で空配列のチェックをしているため、呼び出し元ではチェックしない
        return DB::table('appointment_image')->insert($insertRows);
    }
}
