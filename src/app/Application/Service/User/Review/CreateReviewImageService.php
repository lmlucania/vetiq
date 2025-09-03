<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Domains\Review\Repositories\ReviewImageAttachRepositoryInterface;
use App\Domains\Review\Repositories\ReviewImageStorageRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateReviewImageService
{
    public function __construct(
        private ReviewImageAttachRepositoryInterface $reviewImageAttachRepository,
        private ReviewImageStorageRepositoryInterface $reviewImageStorageRepository,
    ) {
    }

    /**
     * @param int $reviewId
     * @param UploadedFile[] $images
     * @return true
     */
    public function execute(int $reviewId, array $images): bool
    {
        if (empty($images)) {
            return true;
        }

        $insertRows = [];
        foreach ($images as $index => $image) {
            $now    = Carbon::now();
            $s3Path = $this->reviewImageStorageRepository->save($reviewId, $image);

            $insertRows[] = [
                'review_id'     => $reviewId,
                'image_path'    => $s3Path,
                'display_order' => $index + 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        try {
            return $this->reviewImageAttachRepository->attachMany($reviewId, $insertRows);
        } catch (Throwable $e) {
            Log::error('Fail to create review image', ['error' => $e]);
            $this->reviewImageStorageRepository->deleteMany(array_column($insertRows, 'image_path'));
            return false;
        }
    }
}
