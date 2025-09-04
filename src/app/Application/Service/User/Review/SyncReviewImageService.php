<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Application\Dto\Request\ReviewImageDto;
use App\Domains\Review\Repositories\ReviewImageAttachRepositoryInterface;
use App\Domains\Review\Repositories\ReviewImageStorageRepositoryInterface;
use App\Domains\Review\Repositories\ReviewRepositoryInterface;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 画像を同期するサービスクラス
 *
 * 新規画像：S3に保存し、DBにアタッチして並び順を設定
 * 既存画像：不要な画像を削除し、並び順を更新
 */
class SyncReviewImageService
{
    public function __construct(
        private ReviewImageStorageRepositoryInterface $reviewImageStorageRepository,
        private ReviewImageAttachRepositoryInterface $reviewImageAttachRepository,
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    /**
     * @param int $hospitalId
     * @param int $reviewId
     * @param ReviewImageDto[] $dtos
     * @return void
     * @throws Throwable
     */
    public function execute(int $hospitalId, int $reviewId, array $dtos)
    {
        $review = $this->reviewRepository->getByHospitalIdAndId($hospitalId, $reviewId);

        $keepImages = [];
        $newImages  = [];
        foreach ($dtos as $dto) {
            if (! empty($dto->getId())) {
                $keepImages[] = $dto;
                $this->reviewImageAttachRepository->updateDisplayOrderById($dto->getId(), $dto->getDisplayOrder());
            } elseif (! empty($dto->getFile())) {
                $newImages[] = $dto;
            }
        }

        $this->deleteUnkeptImages(keepImages: $keepImages);
        $this->storeNewImages(review: $review, newImages: $newImages);
    }

    /**
     * 不要な画像を削除する
     * @param ReviewImageDto[] $keepImages
     * @return void
     */
    private function deleteUnkeptImages(array $keepImages): void
    {
        $keepIds = array_map(fn (ReviewImageDto $dto) => $dto->getId(), $keepImages);

        $deletePaths = $this->reviewImageAttachRepository->getPathsByExceptIds($keepIds);
        $deleteIds   = $this->reviewImageAttachRepository->getIdsByExceptIds($keepIds);

        $this->reviewImageStorageRepository->deleteMany($deletePaths);
        $this->reviewImageAttachRepository->detachMany($deleteIds);
    }

    /**
     * 新しい画像の保存とアタッチ
     * @param Review $review
     * @param ReviewImageDto[] $newImages
     * @return void
     * @throws Throwable
     */
    private function storeNewImages(Review $review, array $newImages): void
    {
        if (empty($newImages)) {
            return;
        }

        $insertRows = [];
        foreach ($newImages as $dto) {
            $now    = Carbon::now();
            $s3Path = $this->reviewImageStorageRepository->save(reviewId: $review->id, image: $dto->getFile());

            $insertRows[] = [
                'review_id'     => $review->id,
                'image_path'    => $s3Path,
                'display_order' => $dto->getDisplayOrder(),
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        try {
            $this->reviewImageAttachRepository->attachMany($review->id, $insertRows);
        } catch (Throwable $e) {
            Log::error('Failed to attach review images', ['error' => $e]);
            throw $e;
        }
    }
}
