<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\HospitalInfo;

use App\Application\Dto\Request\HospitalImageDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalImageAttachRepositoryInterface;
use App\Domains\Hospital\Repositories\HospitalImageStorageRepositoryInterface;

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
     */
    public function execute(array $dtos)
    {
        $hospitalId = $this->authActorService->getHospitalId();

        $keepImages = [];
        $newImages  = [];
        foreach ($dtos as $dto) {
            if (! empty($dto->getId())) {
                $keepImages[] = $dto;
            } elseif (! empty($dto->getFile())) {
                $newImages[] = $dto;
            }
        }

        $this->syncKeepImages(hospitalId: $hospitalId, keepImages: $keepImages);
        $this->storeNewImages(hospitalId: $hospitalId, newImages: $newImages);
    }

    /**
     * 既存画像の削除・並び替え更新
     * @param int $hospitalId
     * @param HospitalImageDto[] $keepImages
     * @return void
     */
    private function syncKeepImages(int $hospitalId, array $keepImages): void
    {
        $keepIds = array_map(fn(HospitalImageDto $dto) => $dto->getId(), $keepImages);

        $deletePaths = $this->hospitalImageAttachRepository->getPathsByHospitalIdExceptIds(
            hospitalId: $hospitalId,
            ids: $keepIds,
        );
        $this->hospitalImageStorageRepository->deleteMany($deletePaths);

        $deleteIds = $this->hospitalImageAttachRepository->getIdsByHospitalIdExceptiIds(
            hospitalId: $hospitalId,
            ids: $keepIds,
        );
        $this->hospitalImageAttachRepository->detachMany(hospitalId: $hospitalId, ids: $deleteIds);

        foreach ($keepImages as $dto) {
            // 並び順を更新する
            $this->hospitalImageAttachRepository->updateDisplayOrderByHospitalIdAndId(
                hospitalId: $hospitalId,
                id: $dto->getId(),
                displayOrder: $dto->getDisplayOrder(),
            );
        }
    }

    /**
     * 新しい画像の保存とアタッチ
     * @param int $hospitalId
     * @param HospitalImageDto[] $newImages
     * @return void
     */
    private function storeNewImages(int $hospitalId, array $newImages): void
    {
        if (empty($newImages)) {
            return;
        }

        $uploaded = [];
        foreach ($newImages as $dto) {
            $s3Path = $this->hospitalImageStorageRepository->save(hospitalId: $hospitalId, image: $dto->getFile());

            $uploaded[] = [
                'path'          => $s3Path,
                'display_order' => $dto->getDisplayOrder(),
            ];
        }

        $this->hospitalImageAttachRepository->attachMany(hospitalId: $hospitalId, s3pathsWithOrder: $uploaded);
    }
}
