<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Application\Service\AuthStaffService;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use App\Models\MenuModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MenuQueryService implements MenuQueryServiceInterface
{
    use SortableQuery;

    private $sortable = ['id', 'name', 'detail', 'required_time', 'is_published', 'created_at', 'updated_at'];

    public function __construct(
        private readonly AuthStaffService $authStaffService
    ) {
    }
    public function listByCriteria(int $page, int $perPage, string $keyword, array $sort, array $queryParam): LengthAwarePaginator
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $query = MenuModel::query()->where('hospital_id', $hospitalId->getValue());

        $sortedQuery = $this->querySort($query, $this->sortable, $sort);

        $filteredQuery = $sortedQuery->where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('detail', 'LIKE', "%{$keyword}%");
        });

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page)->appends($queryParam);
    }
}
