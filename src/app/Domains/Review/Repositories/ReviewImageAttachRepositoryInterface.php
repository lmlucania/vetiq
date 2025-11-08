<?php

declare(strict_types=1);

namespace App\Domains\Review\Repositories;

interface ReviewImageAttachRepositoryInterface
{
    public function attachMany(int $reviewId, array $insertRows): bool;

    public function detachMany(array $reviewIds): int;

    public function getPathsByIds(array $reviewIds): array;

    public function getPathsByExceptIds(array $reviewIds): array;

    public function getIdsByExceptIds(array $reviewIds): array;

    public function updateDisplayOrderById(int $id, int $displayOrder): int;
}
