<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Review;

class ReviewService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    public function getOwnByUuid(string $uuid): Review
    {
        $review = $this->reviewRepository->getByUuid($uuid);

        if ($review->user_id != $this->authActorService->getUserId()) {
            throw new NotFoundException();
        }

        return $review;
    }
}
