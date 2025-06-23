<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\AppointmentQueryServiceInterface;
use App\Infrastructure\QueryService\FavoriteQueryServiceInterface;
use App\Models\Appointment;
use App\Models\Favorite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AppointmentQueryService implements AppointmentQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['id'];
    private array $defaultSort = ['-id'];

    public function listByUserId(int $userId, int $page, int $perPage, string $keyword, array $sort, $queryParam): LengthAwarePaginator
    {
        $latestStatus = DB::table('appointment_status_histories')
            ->select('appointment_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('appointment_id');

        $query = Appointment::query()
            ->join('pets', 'appointments.pet_id', '=', 'pets.id')
            ->joinSub($latestStatus, 'latest_status', fn($join) =>
            $join->on('appointments.id', '=', 'latest_status.appointment_id')
            )
            ->join('appointment_status_histories as ash', 'ash.id', '=', 'latest_status.latest_id')
            ->where('pets.user_id', $userId)
            ->select('appointments.*', 'ash.status as latest_status', 'ash.created_at as status_updated_at');

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        return $sortedQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
