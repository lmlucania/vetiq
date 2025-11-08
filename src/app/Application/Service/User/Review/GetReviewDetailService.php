<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Domains\Review\Repositories\ReviewRepositoryInterface;
use App\Models\Review;

class GetReviewDetailService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    public function execute(int $hospitalId, int $id): Review
    {
        return $this->reviewRepository->getByHospitalIdAndIdWithImagesAndHospital(
            hospitalId: $hospitalId,
            id: $id,
        );
    }
}
