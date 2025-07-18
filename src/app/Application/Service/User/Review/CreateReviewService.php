<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Review\Enum\Rating;
use App\Domains\Review\Repository\ReviewRepositoryInterface;
use Illuminate\Support\Str;

class CreateReviewService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalRepositoryInterface $hospitalRepository,
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    public function execute(
        int $hospitalId,
        Rating $rating,
        string $title,
        ?string $body,
    ) {
        return $this->reviewRepository->create(
            hospitalId: $this->hospitalRepository->getById($hospitalId)->id,
            userId: $this->authActorService->getUserId(),
            rating: $rating,
            title: $title,
            body: $body,
        );
    }
}
