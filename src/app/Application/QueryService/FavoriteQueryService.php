<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\FavoriteQueryServiceInterface;
use App\Models\Favorite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FavoriteQueryService implements FavoriteQueryServiceInterface
{
    use SortableQuery;

    private $sortable = ['id', 'name'];

    public function listByCriteria(int $userId, int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        $query = Favorite::query()
            ->where('user_id', $userId)
            ->join('hospitals', 'favorites.hospital_id', '=', 'hospitals.id')
            ->select('favorites.id', 'favorites.hospital_id', 'hospitals.name')
            ->orderBy('favorites.id', 'desc');;

        $sortedQuery = $this->querySort($query, $this->sortable, $sort);

        $filteredQuery = $sortedQuery->where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        });

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
