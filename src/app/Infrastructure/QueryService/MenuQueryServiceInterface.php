<?php

declare(strict_types=1);

namespace App\Infrastructure\QueryService;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface MenuQueryServiceInterface
{
    public function listByCriteria(int $page, int $perPage, string $keyword, array $sort):LengthAwarePaginator;

    public function querySort(Builder $query, array $sortable, array $sortParams): Builder;
}
