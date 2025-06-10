<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use App\Models\Menu;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MenuQueryService implements MenuQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['id', 'name', 'detail', 'required_time', 'is_published', 'created_at', 'updated_at'];
    private array $defaultSort = ['id'];

    public function listByCriteria(int $hospitalId, int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        $query = Menu::query()->where('hospital_id', $hospitalId);

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        $filteredQuery = $sortedQuery->where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('detail', 'LIKE', "%{$keyword}%");
        });

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
