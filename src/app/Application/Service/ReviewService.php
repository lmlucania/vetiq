<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Infrastructure\Repositories\HospitalRepository;
use App\Models\Review;

class ReviewService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ReviewRepositoryInterface $reviewRepository,
        private HospitalRepository $hospitalRepository,
    ) {
    }

    public function getOwnByUuidInHospital(string $hospitalUuid, string $reviewUuid): Review
    {
        $hospital = $this->hospitalRepository->getByUuid($hospitalUuid);
        $review   = $this->reviewRepository->getByUuidInHospital($hospital->id, $reviewUuid);

        if ($review->user_id != $this->authActorService->getUserId()) {
            throw new NotFoundException();
        }

        return $review;
    }
}
