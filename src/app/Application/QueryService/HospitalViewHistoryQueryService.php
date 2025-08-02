<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\HospitalViewHistoryQueryServiceInterface;
use App\Models\HospitalViewHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HospitalViewHistoryQueryService implements HospitalViewHistoryQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['updated_at'];
    private array $defaultSort = ['-updated_at'];

    public function listByCriteria(int $userId, int $page, int $perPage, array $sort, array $queryParam): LengthAwarePaginator
    {
        $query = HospitalViewHistory::query()
            ->where('user_id', $userId)
            ->select(
                'hospital_view_histories.user_id',
                'hospital_view_histories.hospital_id',
                'hospital_view_histories.updated_at',
            );

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        return $sortedQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
