<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Application\Service\AuthStaffService;
use App\Infrastructure\QueryService\VetQueryServiceInterface;
use App\Models\VetModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VetQueryService implements VetQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['id', 'last_name', 'first_name', 'accept_appointment', 'remark', 'created_at', 'updated_at'];
    private array $defaultSort = ['id'];

    public function __construct(
        private readonly AuthStaffService $authStaffService
    ) {
    }

    public function listByCriteria(int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $query = VetModel::query()->where('hospital_id', $hospitalId->getValue());

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        $filteredQuery = $sortedQuery->where(function ($query) use ($keyword) {
            $query->where('last_name', 'LIKE', "%{$keyword}%")
                ->orWhere('first_name', 'LIKE', "%{$keyword}%");
        });

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
