<?php

declare(strict_types=1);

namespace App\Infrastructure\QueryService;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface FavoriteQueryServiceInterface
{
    public function listByCriteria(int $userId, int $page, int $perPage, string $keyword, array $sort, array $queryParam):LengthAwarePaginator;

    public function querySort(Builder $query, array $sortable, array $sortParams): Builder;
}
