<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Review\Enum\Rating;
use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;

class UpdateReviewService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    public function execute(
        string $hospitalUuid,
        string $uuid,
        Rating $rating,
        string $title,
        ?string $body,
    ): bool {
        $review = $this->reviewRepository->getByHospitalUuidAndUuid(
            hospitalUuid: $hospitalUuid,
            uuid: $uuid,
        );

        if ($review->user_id != $this->authActorService->getUserId()) {
            throw new NotFoundException();
        }

        return $this->reviewRepository->update(
            id: $review->id,
            rating: $rating,
            title: $title,
            body: $body,
        );
    }
}
