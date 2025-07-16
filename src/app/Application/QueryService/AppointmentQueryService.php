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
                'appointments.appointment_at as appt_appointment_at',
                'ash.status',
                'appointments.created_at as appt_created_at',
                'ash.created_at as status_created_at',
                'pets.id as pet_id',
                'pets.name as pet_name',
                'pets.gender as pet_gender',
                'pets.birthday as pet_birthday',
                'pets.started_care_at as pet_started_care_at',
                'pets.remark as pet_remark',
                'hospitals.id as hospital_id',
                'hospitals.name as hospital_name',
                'hospitals.phone as hospital_phone',
                'hospitals.post_code as hospital_post_code',
                'hospitals.prefecture as hospital_prefecture',
                'hospitals.address1 as hospital_address1',
                'hospitals.address2 as hospital_address2',
                'menus.id as menu_id',
                'menus.name as menu_name',
                'menus.detail as menu_detail',
                'menus.required_time as menu_required_time',
                'menus.is_published as menu_is_published',
                'vets.id as vet_id',
                'vets.last_name as vet_last_name',
                'vets.first_name as vet_first_name',
                'vets.accept_appointment as vet_accept_appointment',
            );

        $sortedQuery = $this->querySort($query, $this->sortable, $sort ?: $this->defaultSort);

        return $sortedQuery->paginate($perPage, ['*'], 'page', $page);
    }
}
