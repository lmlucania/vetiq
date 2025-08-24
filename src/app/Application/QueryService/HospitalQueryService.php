<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\Dto\Request\TimeRangeDto;
use App\Application\QueryService\Traits\SortableQuery;
use App\Domains\Schedule\Enum\DayOfWeek;
use App\Infrastructure\QueryService\HospitalQueryServiceInterface;
use App\Models\Hospital;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class HospitalQueryService implements HospitalQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['name', 'prefecture'];
    private array $defaultSort = ['prefecture', 'address1', 'address2'];

    public function listByCriteria(
        int $page,
        int $perPage,
        string $keyword,
        array $tagIds,
        array $prefectureCodes,
        array $sort,
        string $date,
        TimeRangeDto $timeRange,
        array $queryParam
    ): LengthAwarePaginator {
        $query = Hospital::query();

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        $filteredQuery = $this->applyFilter($sortedQuery, $keyword, $tagIds, $prefectureCodes);

        if (! empty($date)) {
            $dow = DayOfWeek::fromCarbon(Carbon::createFromFormat('Y-m-d', $date));

            $filteredQuery
                ->whereHas('businessHours', function ($subQuery) use ($dow) {
                    // 通常の営業日程
                    $subQuery->where('day_of_week', $dow);
                })
                ->whereDoesntHave('exceptionHours', function ($subQuery) use ($date) {
                    // 臨時休業になっていない
                    $subQuery->where('date', $date)
                        ->where('is_closed', true);
                });
        }

        if (! empty($timeRange)) {
            // 通常の営業時間
            $filteredQuery
                ->whereHas('businessHours', function ($subQuery) use ($timeRange) {
                    $subQuery
                        ->where('start_time', '<=', $timeRange->getStartTime())
                        ->where('end_time', '>=', $timeRange->getEndTime());
                });
        }

        return $filteredQuery->paginate($perPage, ['*'], 'page', $page);
    }

    private function applyFilter(Builder $query, ?string $keyword, array $tagIds, array $prefectureCodes): Builder
    {
        $trimmed = trim((string) $keyword);
        if ($trimmed !== '') {
            $query = $query->where('name', 'LIKE', "%{$trimmed}%");
        }

        if (! empty($tagIds)) {
            $query = $query->whereHas('tags', function ($subQuery) use ($tagIds) {
                $subQuery->whereIn('tags.id', $tagIds);
            });
        }

        if (! empty($prefectureCodes)) {
            $query = $query->whereIn('prefecture', $prefectureCodes);
        }

        return $query;
    }
}
