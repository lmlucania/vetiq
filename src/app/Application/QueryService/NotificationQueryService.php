<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use App\Infrastructure\QueryService\NotificationQueryServiceInterface;
use App\Models\Menu;
use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationQueryService implements NotificationQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['published_at', 'is_published'];
    private array $defaultSort = ['-published_at'];

    public function listByCriteria(int $hospitalId, int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        $query = Notification::query()->where('hospital_id', $hospitalId);

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        $filteredQuery = $sortedQuery->where(function ($query) use ($keyword) {
            $query->where('title', 'LIKE', "%{$keyword}%")
                ->orWhere('detail', 'LIKE', "%{$keyword}%");
        });

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
