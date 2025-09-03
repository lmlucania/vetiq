<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Repositories\HospitalImageAttachRepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 病院と保存先のパスとの紐付け、解除をするリポジトリクラス
 * 中間テーブルを操作する責務を持つ
 * 画像ファイルを S3 にアップロードは別のクラスで行う @see S3HospitalImageStorageRepository
 */
class HospitalImageAttachRepository implements HospitalImageAttachRepositoryInterface
{
    public function attachMany(int $hospitalId, array $s3pathsWithOrder): bool
    {
        if (empty($s3pathsWithOrder)) {
            return true;
        }

        $records = [];
        foreach ($s3pathsWithOrder as $pathWithOrder) {
            $records[] = [
                'hospital_id'   => $hospitalId,
                'image_path'    => $pathWithOrder['path'],
                'display_order' => $pathWithOrder['display_order'],
            ];
        }

        try {
            DB::table('hospital_image')->insert($records);
            return true;
        } catch (Throwable $e) {
            Log::error('Failed to attach appointment images', [
                'hospital_id' => $hospitalId,
                'paths'       => $pathWithOrder['path'],
                'error'       => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function detachMany(int $hospitalId, array $ids): int
    {
        return DB::table('hospital_image')
            ->where('hospital_id', $hospitalId)
            ->whereIn('id', $ids)
            ->delete();
    }

    public function getPathsByHospitalIdAndIds(int $hospitalId, array $ids): array
    {
        return DB::table('hospital_image')
            ->where('hospital_id', $hospitalId)
            ->whereIn('id', $ids)
            ->pluck('image_path')
            ->toArray();
    }

    public function getPathsByHospitalIdExceptIds(int $hospitalId, array $ids): array
    {
        return $this->buildQuery($hospitalId, $ids)
            ->pluck('image_path')
            ->toArray();
    }

    public function getIdsByHospitalIdExceptiIds(int $hospitalId, array $ids): array
    {
        return $this->buildQuery($hospitalId, $ids)
            ->pluck('id')
            ->toArray();
    }

    public function updateDisplayOrderByHospitalIdAndId(int $hospitalId, int $id, int $displayOrder): int
    {
        return DB::table('hospital_image')
            ->where('hospital_id', $hospitalId)
            ->where('id', $id)
            ->update(['display_order' => $displayOrder]);
    }

    private function buildQuery(int $hospitalId, array $ids): Builder
    {
        return DB::table('hospital_image')
            ->where('hospital_id', $hospitalId)
            ->whereNotIn('id', $ids);
    }
}
