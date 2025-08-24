<?php

declare(strict_types=1);

namespace App\Infrastructure\QueryService;

use App\Application\Dto\Request\TimeRangeDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface HospitalQueryServiceInterface
{
    public function listByCriteria(
        int $page,
        int $perPage,
        string $keyword,
        array $tagIds,
        array $prefectureCodes,
        array $sort,
        string $date,
        TimeRangeDto $timeRange,
        array $queryParam
    ):LengthAwarePaginator;

    public function querySort(Builder $query, array $sortable, array $sortParams): Builder;
}
