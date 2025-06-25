<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use App\Application\QueryService\Traits\SortableQuery;
use App\Infrastructure\QueryService\AppointmentQueryServiceInterface;
use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AppointmentQueryService implements AppointmentQueryServiceInterface
{
    use SortableQuery;

    private array $sortable    = ['appt'];
    private array $defaultSort = ['-appt'];

    public function listByUserId(int $userId, int $page, int $perPage, array $sort, array $queryParam): LengthAwarePaginator
    {
        $latestStatus = DB::table('appointment_status_histories')
            ->select('appointment_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('appointment_id');

        $query = Appointment::query()
            ->join('pets', function ($subQuery) use ($userId) {
                $subQuery->on('appointments.pet_id', '=', 'pets.id')
                    ->where('pets.user_id', '=', $userId);
            })
            ->joinSub(
                $latestStatus,
                'latest_status',
                fn ($subQuery) => $subQuery->on('appointments.id', '=', 'latest_status.appointment_id'),
            )
            ->join('appointment_status_histories as ash', 'ash.id', '=', 'latest_status.latest_id')
            // N+1を防ぐために、AppointmentTransformerで取得する関連データを結合する
            ->join('hospitals', 'appointments.hospital_id', '=', 'hospitals.id')
            ->join('menus', 'appointments.menu_id', '=', 'menus.id')
            ->leftJoin('vets', 'appointments.vet_id', '=', 'vets.id')
            ->select(
                'appointments.id as appt',
                'appointments.*',
                'hospitals.*',
                'menus.*',
                'vets.*',
                'ash.status',
                'ash.created_at as status_created_at',
            );

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        return $sortedQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
