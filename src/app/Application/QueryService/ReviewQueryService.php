<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\ReviewQueryServiceInterface;
use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ReviewQueryService implements ReviewQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['id', 'rating'];
    private array $defaultSort = ['-id'];

    public function listByHospitalUuid(
        string $hospitalUuid,
        int $page,
        int $perPage,
        string $keyword,
        array $rating,
        array $sort,
        array $queryParam
    ):LengthAwarePaginator {
        $query = Review::query()
            ->join('hospitals', function ($join) use ($hospitalUuid) {
                $join->on('reviews.hospital_id', '=', 'hospitals.id')
                    ->where('hospitals.uuid', '=', $hospitalUuid);
            })
            ->select('reviews.id', 'reviews.uuid', 'reviews.hospital_id', 'reviews.rating', 'reviews.title', 'reviews.body');

        return $this->applySortingAndFiltering($query, $page, $perPage, $keyword, $rating, $sort);
    }

    public function listByHospitalId(
        int $hospitalId,
        int $page,
        int $perPage,
        string $keyword,
        array $rating,
        array $sort,
        array $queryParam
    ):LengthAwarePaginator {
        $query = Review::query()
            ->where('hospital_id', $hospitalId)
            ->join('hospitals', function ($join) {
                $join->on('reviews.hospital_id', '=', 'hospitals.id');
            })
            ->select('reviews.id', 'reviews.uuid', 'reviews.hospital_id', 'reviews.rating', 'reviews.title', 'reviews.body');

        return $this->applySortingAndFiltering($query, $page, $perPage, $keyword, $rating, $sort);
    }

    public function listByUserId(
        int $userId,
        int $page,
        int $perPage,
        string $keyword,
        array $rating,
        array $sort,
        array $queryParam
    ): LengthAwarePaginator {
        $query = Review::where('user_id', $userId)
            ->join('hospitals', function ($join) {
                $join->on('reviews.hospital_id', '=', 'hospitals.id');
            })
            ->select('reviews.id', 'reviews.uuid', 'reviews.hospital_id', 'reviews.rating', 'reviews.title', 'reviews.body');

        return $this->applySortingAndFiltering($query, $page, $perPage, $keyword, $rating, $sort);
    }

    private function applySortingAndFiltering(Builder $query, int $page, int $perPage, string $keyword, array $rating, array $sort): LengthAwarePaginator
    {
        $sortedQuery   = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);
        $filteredQuery = $this->applyFilter($sortedQuery, $keyword, $rating);
        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }

    private function applyFilter(Builder $query, ?string $keyword, array $ratings): Builder
    {
        $trimmed = trim((string) $keyword);
        if ($trimmed !== '') {
            $query = $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('body', 'LIKE', "%{$keyword}%");
            });
        }

        if (! empty($ratings)) {
            $query = $query->whereIn('rating', $ratings);
        }

        return $query;
    }
}
