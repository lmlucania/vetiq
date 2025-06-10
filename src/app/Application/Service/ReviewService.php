<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Review\Enum\Rating;
use App\Domains\Review\Repository\ReviewRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Infrastructure\QueryService\ReviewQueryServiceInterface;
use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ReviewService
{
    public function __construct(
        private AuthActorService $authActorService,
        private HospitalRepositoryInterface $hospitalRepository,
        private ReviewRepositoryInterface $reviewRepository,
        private ReviewQueryServiceInterface $reviewQueryService,
    ) {
    }

    public function getByUuidInHospital(string $hospitalUuid, string $uuid): Review
    {
        return $this->reviewRepository->getByUuidInHospital(
            hospitalUuid: $hospitalUuid,
            reviewUuid: $uuid,
        );
    }

    public function create(
        string $hospitalUuid,
        Rating $rating,
        string $title,
        ?string $body,
    ) {
        return $this->reviewRepository->create(
            uuid: (string)Str::uuid(),
            hospitalId: $this->hospitalRepository->getByUuid($hospitalUuid)->id,
            userId: $this->authActorService->getUserId(),
            rating: $rating,
            title: $title,
            body: $body,
        );
    }

    public function updateOwn(
        string $hospitalUuid,
        string $uuid,
        Rating $rating,
        string $title,
        ?string $body,
    ): bool {
        $review = $this->getOwnByUuidInHospital(
            hospitalUuid: $hospitalUuid,
            uuid: $uuid,
        );

        return $this->reviewRepository->update(
            id: $review->id,
            rating: $rating,
            title: $title,
            body: $body,
        );
    }

    public function listOwn(
        int $page,
        int $perPage,
        string $keyword,
        array $rating,
        array $sort,
        array $queryParam
    ): LengthAwarePaginator {
        $userId = $this->authActorService->getUserId();

        return $this->reviewQueryService->listByCriteriaInUser(
            userId: $userId,
            page:$page,
            perPage: $perPage,
            keyword: $keyword,
            rating: $rating,
            sort: $sort,
            queryParam: $queryParam,
        );
    }

    private function getOwnByUuidInHospital(string $hospitalUuid, string $uuid): Review
    {
        $review = $this->reviewRepository->getByUuidInHospital(
            hospitalUuid: $hospitalUuid,
            reviewUuid: $uuid,
        );

        if ($review->user_id != $this->authActorService->getUserId()) {
            throw new NotFoundException();
        }

        return $review;
    }
}
