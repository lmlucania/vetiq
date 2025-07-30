<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\HospitalQueryServiceInterface;
use App\Models\Hospital;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class HospitalQueryService implements HospitalQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['name', 'prefecture'];
    private array $defaultSort = ['prefecture', 'address1', 'address2'];

    public function listByCriteria(int $page, int $perPage, string $keyword, array $tagIds, array $prefectureCodes, array $sort, array $queryParam): LengthAwarePaginator
    {
        $query = Hospital::query();

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        $filteredQuery = $this->applyFilter($sortedQuery, $keyword, $tagIds, $prefectureCodes);

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }

    private function applyFilter(Builder $query, ?string $keyword, array $tagIds, array $prefectureCodes): Builder
    {
        $trimmed = trim((string) $keyword);
        if ($trimmed !== '') {
            $query = $query->where('name', 'LIKE', "%{$trimmed}%");
        }

        if (!empty($tagIds)) {
            $query = $query->whereHas('tags', function ($subQuery) use ($tagIds) {
                $subQuery->whereIn('tags.id', $tagIds);
            });
        }

        if (!empty($prefectureCodes)) {
            $query = $query->whereIn('prefecture', $prefectureCodes);
        }

        return $query;
    }
}
