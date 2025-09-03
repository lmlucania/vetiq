<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\HospitalInfo;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalImageAttachRepositoryInterface;
use App\Domains\Hospital\Repositories\HospitalImageStorageRepositoryInterface;

class SyncHospitalImageService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalImageStorageRepositoryInterface $hospitalImageStorageRepository,
        private HospitalImageAttachRepositoryInterface $hospitalImageAttachRepository,
    ) {
    }

    public function execute(array $images)
    {
        $hospitalId = $this->authActorService->getHospitalId();

        $keeps = [];
        $new   = [];
        foreach ($images as $index => $image) {
            // リクエストの順番を並び順とする
            if (! empty($image['id'])) {
                $keeps[] = [
                    'id'            => (int)$image['id'],
                    'display_order' => $index + 1,
                ];
                continue;
            }

            if (! empty($image['file'])) {
                $new[] = [
                    'file'          => $image['file'],
                    'display_order' => $index + 1,
                ];
            }
        }

        $this->deleteImages(
            hospitalId: $hospitalId,
            keeps: $keeps,
        );

        $this->storeImages(
            hospitalId: $hospitalId,
            new: $new,
        );
    }

    /**
     * @param int $hospitalId
     * @param array $keeps
     * @return void
     */
    private function deleteImages(int $hospitalId, array $keeps): void
    {
        $keepIds     = array_column($keeps, 'id');
        $deletePaths = $this->hospitalImageAttachRepository->getPathsByHospitalIdExceptIds(
            hospitalId: $hospitalId,
            ids: $keepIds,
        );

        $this->hospitalImageStorageRepository->deleteMany($deletePaths);

        $deleteIds = $this->hospitalImageAttachRepository->getIdsByHospitalIdExceptiIds(
            hospitalId: $hospitalId,
            ids: $keepIds,
        );
        $this->hospitalImageAttachRepository->detachMany(
            hospitalId: $hospitalId,
            ids: $deleteIds,
        );

        foreach ($keeps as $keep) {
            $this->hospitalImageAttachRepository->updateDisplayOrderByHospitalIdAndId(
                hospitalId: $hospitalId,
                id: $keep['id'],
                displayOrder: $keep['display_order'],
            );
        }
    }

    /**
     * @param int $hospitalId
     * @param array $new
     * @return void
     */
    private function storeImages(int $hospitalId, array $new): void
    {
        if (empty($new)) {
            return;
        }

        foreach ($new as $image) {
            $s3Path = $this->hospitalImageStorageRepository->save(
                hospitalId: $hospitalId,
                image: $image['file'],
            );

            $uploaded[] = [
                'path'          => $s3Path,
                'display_order' => $image['display_order'],
            ];
        }

        $this->hospitalImageAttachRepository->attachMany(
            hospitalId: $hospitalId,
            s3pathsWithOrder: $uploaded,
        );
    }
}
