<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\HospitalInfo;

use App\Application\Dto\Request\HospitalImageDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalImageAttachRepositoryInterface;
use App\Domains\Hospital\Repositories\HospitalImageStorageRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 病院画像を同期するサービスクラス
 *
 * 新規画像：S3に保存し、DBにアタッチして並び順を設定
 * 既存画像：不要な画像を削除し、並び順を更新
 */
class SyncHospitalImageService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalImageStorageRepositoryInterface $hospitalImageStorageRepository,
        private HospitalImageAttachRepositoryInterface $hospitalImageAttachRepository,
    ) {
    }

    /**
     * @param HospitalImageDto[] $dtos
     * @return void
     * @throws Throwable
     */
    public function execute(array $dtos)
    {
        $hospitalId = $this->authActorService->getHospitalId();

        $keepImages = [];
        $newImages  = [];
        foreach ($dtos as $dto) {
            if (! empty($dto->getId())) {
                $keepImages[] = $dto;
                $this->hospitalImageAttachRepository->updateDisplayOrderByHospitalIdAndId(
                    hospitalId: $hospitalId,
                    id: $dto->getId(),
                    displayOrder: $dto->getDisplayOrder(),
                );
            } elseif (! empty($dto->getFile())) {
                $newImages[] = $dto;
            }
        }

        $this->deleteUnkeptImages(hospitalId: $hospitalId, keepImages: $keepImages);
        $this->storeNewImages(hospitalId: $hospitalId, newImages: $newImages);
    }

    /**
     * 不要な画像を削除する
     * @param int $hospitalId
     * @param HospitalImageDto[] $keepImages
     * @return void
     */
    private function deleteUnkeptImages(int $hospitalId, array $keepImages): void
    {
        $keepIds = array_map(fn (HospitalImageDto $dto) => $dto->getId(), $keepImages);

        $deletePaths = $this->hospitalImageAttachRepository->getPathsByHospitalIdExceptIds(
            hospitalId: $hospitalId,
            ids: $keepIds,
        );
        $deleteIds = $this->hospitalImageAttachRepository->getIdsByHospitalIdExceptIds(
            hospitalId: $hospitalId,
            ids: $keepIds,
        );

        $this->hospitalImageStorageRepository->deleteMany($deletePaths);
        $this->hospitalImageAttachRepository->detachMany(hospitalId: $hospitalId, ids: $deleteIds);
    }

    /**
     * 新しい画像の保存とアタッチ
     * @param int $hospitalId
     * @param HospitalImageDto[] $newImages
     * @return void
     * @throws Throwable
     */
    private function storeNewImages(int $hospitalId, array $newImages): void
    {
        if (empty($newImages)) {
            return;
        }

        $insertRows = [];
        foreach ($newImages as $dto) {
            $now    = Carbon::now();
            $s3Path = $this->hospitalImageStorageRepository->save(hospitalId: $hospitalId, image: $dto->getFile());

            $insertRows[] = [
                'hospital_id'   => $hospitalId,
                'image_path'    => $s3Path,
                'display_order' => $dto->getDisplayOrder(),
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        try {
            $this->hospitalImageAttachRepository->attachMany($hospitalId, $insertRows);
        } catch (Throwable $e) {
            Log::error('Failed to attach hospital images', ['error' => $e]);
            throw $e;
        }
    }
}
