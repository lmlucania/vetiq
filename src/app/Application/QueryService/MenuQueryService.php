<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Infrastructure\QueryService\MenuQueryServiceInterface;
use App\Models\MenuModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MenuQueryService implements MenuQueryServiceInterface
{
    use SortableQuery;

    private $sortable = ['id', 'name', 'detail', 'required_time', 'is_published', 'created_at', 'updated_at'];

    public function __construct(
        private readonly HospitalFactory $hospitalFactory
    ) {
    }
    public function listByCriteria(int $page, int $perPage, string $keyword, array $sort): LengthAwarePaginator
    {
        $hospitalEntity = $this->hospitalFactory::createEntityFromAuthStaff();

        $query = MenuModel::query()->where('hospital_id', $hospitalEntity->getId()->getValue());

        $sortedQuery = $this->querySort($query, $this->sortable, $sort);

        $filteredQuery = $sortedQuery->where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('detail', 'LIKE', "%{$keyword}%");
        });

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
