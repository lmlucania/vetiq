<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Infrastructure\QueryService\VetQueryServiceInterface;
use App\Models\VetModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VetQueryService implements VetQueryServiceInterface
{
    use SortableQuery;

    private $sortable = ['id', 'last_name', 'first_name', 'accept_appointment', 'remark', 'created_at', 'updated_at'];

    public function __construct(
        private readonly HospitalFactory $hospitalFactory
    ) {
    }

    public function listByCriteria(int $page, int $perPage, string $keyword, array $sort): LengthAwarePaginator
    {
        $hospitalEntity = $this->hospitalFactory::createEntityFromAuthStaff();

        $query = VetModel::query()->where('hospital_id', $hospitalEntity->getId()->getValue());

        $sortedQuery = $this->querySort($query, $this->sortable, $sort);

        $filteredQuery = $sortedQuery->where(function ($query) use ($keyword) {
            $query->where('last_name', 'LIKE', "%{$keyword}%")
                ->orWhere('first_name', 'LIKE', "%{$keyword}%");
        });

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
