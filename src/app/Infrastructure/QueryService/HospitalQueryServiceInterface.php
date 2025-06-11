<?php

declare(strict_types=1);

namespace App\Infrastructure\QueryService;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface HospitalQueryServiceInterface
{
    public function listByCriteria(int $page, int $perPage, string $keyword, array $sort, $queryParam):LengthAwarePaginator;

    public function querySort(Builder $query, array $sortable, array $sortParams): Builder;
}
