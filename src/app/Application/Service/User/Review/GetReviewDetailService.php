<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Models\Review;

class GetReviewDetailService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    public function execute(string $hospitalUuid, string $uuid): Review
    {
        return $this->reviewRepository->getByUuidInHospital(
            hospitalUuid: $hospitalUuid,
            reviewUuid: $uuid,
        );
    }
}
