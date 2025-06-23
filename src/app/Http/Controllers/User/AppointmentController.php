<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\Appointment\CancelAppointmentService;
use App\Application\Service\User\Appointment\CreateAppointmentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        private CreateAppointmentService $createAppointmentService,
        private CancelAppointmentService $cancelAppointmentService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
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
