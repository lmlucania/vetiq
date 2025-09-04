<?php

declare(strict_types=1);

namespace App\Application\Service\User\Review;

use App\Application\Dto\Request\ReviewImageDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\Review\Enum\Rating;
use App\Domains\Review\Repositories\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use Throwable;

class UpdateReviewService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ReviewRepositoryInterface $reviewRepository,
        private SyncReviewImageService $syncReviewImageService,
    ) {
    }

    /**
     * @param int $hospitalId
     * @param int $id
     * @param Rating $rating
     * @param string $title
     * @param string|null $body
     * @param ReviewImageDto[] $dtos
     * @return bool
     * @throws NotFoundException
     * @throws Throwable
     */
    public function execute(
        int $hospitalId,
        int $id,
        Rating $rating,
        string $title,
        ?string $body,
        array $dtos,
    ): bool {
        $review = $this->reviewRepository->getByHospitalIdAndId(
            hospitalId: $hospitalId,
            id: $id,
        );

        if ($review->user_id != $this->authActorService->getUserId()) {
            throw new NotFoundException();
        }

        $this->syncReviewImageService->execute($hospitalId, $review->id, $dtos);

        return $this->reviewRepository->update(
            id: $review->id,
            rating: $rating,
            title: $title,
            body: $body,
        );
    }
}
