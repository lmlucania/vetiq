<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\Appointment\CancelAppointmentService;
use App\Application\Service\User\Appointment\CreateAppointmentService;
use App\Application\Service\User\Appointment\GetAppointmentDetailService;
use App\Application\Service\User\Appointment\GetMyAppointmentsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Appointment\IndexAppointmentRequest;
use App\Http\Requests\User\Appointment\StoreAppointmentRequest;
use App\Transformers\AppointmentTransformer;
use App\Transformers\AppointmentWithStatusHistoryTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class AppointmentController extends Controller
{
    public function __construct(
        private GetMyAppointmentsService $getMyAppointmentsService,
        private CreateAppointmentService $createAppointmentService,
        private GetAppointmentDetailService $getAppointmentDetailService,
        private CancelAppointmentService $cancelAppointmentService,
    ) {
    }

    /**
     * @lrd:start
     * 予約の一覧
     * @lrd:end
     */
    public function index(IndexAppointmentRequest $request)
    {
        $paginator = $this->getMyAppointmentsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );
        return fractal($paginator->getCollection(), new AppointmentTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * @lrd:start
     * 予約を作成
     * @lrd:end
     */
    public function store(StoreAppointmentRequest $request)
    {
        $success = $this->createAppointmentService->execute(
            petId: $request->getPetId(),
            hospitalId: $request->getHospitalId(),
            menuId: $request->getMenuId(),
            vetId: $request->getVetId(),
            appointmentAt: $request->getAppointmentAt(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 予約の詳細
     * @lrd:end
     */
    public function show(int $id)
    {
        $appointment = $this->getAppointmentDetailService->execute($id);
        return fractal($appointment, new AppointmentWithStatusHistoryTransformer())
            ->parseIncludes(['pet', 'menu', 'hospital', 'vet'])
            ->respond();
    }

    /**
     * @lrd:start
     * 予約をキャンセル
     * @lrd:end
     */
    public function cancel(int $id)
    {
        $success = $this->cancelAppointmentService->execute(
            appointmentId: $id,
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
