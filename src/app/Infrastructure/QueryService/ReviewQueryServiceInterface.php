<?php

declare(strict_types=1);

namespace App\Infrastructure\QueryService;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface ReviewQueryServiceInterface
{
    public function listByHospitalId(
        int $hospitalId,
        int $page,
        int $perPage,
        string $keyword,
        array $rating,
        array $sort,
        array $queryParam
    ):LengthAwarePaginator;

    public function listByUserId(
        int $userId,
        int $page,
        int $perPage,
        string $keyword,
        array $rating,
        array $sort,
        array $queryParam
    ):LengthAwarePaginator;

    public function querySort(Builder $query, array $sortable, array $sortParams): Builder;
}
