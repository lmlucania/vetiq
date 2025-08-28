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
        array $addresses,
        array $sort,
        string $date,
        TimeRangeDto $timeRange,
        array $dayOfWeek,
        array $queryParam
    ): LengthAwarePaginator {
        $query = Hospital::query();

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        $filteredQuery = $this->applyFilter($sortedQuery, $keyword, $tagIds, $prefectureCodes);

        if (! empty($addresses)) {
            $filteredQuery->where(function ($subQuery) use ($addresses) {
                foreach ($addresses as $addr) {
                    $subQuery->orWhere('address1', 'like', "%{$addr}%");
                }
            });
        }

        if (! empty($date)) {
            $dowByDate = DayOfWeek::fromCarbon(Carbon::createFromFormat('Y-m-d', $date));

            $filteredQuery
                ->whereHas('businessHours', function ($subQuery) use ($dowByDate) {
                    // 通常の営業日程
                    $subQuery->where('day_of_week', $dowByDate);
                })
                ->whereDoesntHave('exceptionHours', function ($subQuery) use ($date) {
                    // 臨時休業になっていない
                    $subQuery->where('date', $date)
                        ->where('is_closed', true);
                });
        }

        if (! $timeRange->empty()) {
            // 通常の営業時間
            $filteredQuery
                ->whereHas('businessHours', function ($subQuery) use ($timeRange, $dayOfWeek) {

                    if (!empty($dayOfWeek)) {
                        // 曜日が渡された場合は、指定された曜日の営業時間の絞り込みをする
                        $subQuery->whereIn('day_of_week', $dayOfWeek);
                    }

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
