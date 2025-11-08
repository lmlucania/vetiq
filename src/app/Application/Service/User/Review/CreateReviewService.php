<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Review\Enum\Rating;
use App\Domains\Review\Repositories\ReviewRepositoryInterface;
use Illuminate\Http\UploadedFile;

class CreateReviewService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalRepositoryInterface $hospitalRepository,
        private ReviewRepositoryInterface $reviewRepository,
        private CreateReviewImageService $createReviewImageService,
    ) {
    }

    /**
     * @param int $hospitalId
     * @param Rating $rating
     * @param string $title
     * @param string|null $body
     * @param UploadedFile[] $images
     * @return bool
     */
    public function execute(
        int $hospitalId,
        Rating $rating,
        string $title,
        ?string $body,
        array $images,
    ): bool {
        $review = $this->reviewRepository->create(
            hospitalId: $this->hospitalRepository->getById($hospitalId)->id,
            userId: $this->authActorService->getUserId(),
            rating: $rating,
            title: $title,
            body: $body,
        );

        return $this->createReviewImageService->execute(
            reviewId: $review->id,
            images: $images,
        );
    }
}
