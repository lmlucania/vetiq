<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\ShowHospitalInfoByAuthStaffUseCase;
use App\Application\UseCase\Hospital\UpdateHospitalInfoByAuthStaffUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\StoreHospitalRequest;
use App\Transformers\HospitalTransformer;

class HospitalController extends Controller
{
    public function __construct(
        private readonly ShowHospitalInfoByAuthStaffUseCase $showHospitalInfoByAuthStaffUseCase,
        private readonly UpdateHospitalInfoByAuthStaffUseCase $updateHospitalInfoByAuthStaffUseCase
    ) {
    }

    /**
     * @lrd:start
     * 病院情報を取得
     * @lrd:end
     */
    public function show()
    {
        $dto = $this->showHospitalInfoByAuthStaffUseCase->show();
        return fractal($dto, new HospitalTransformer())->respond();
    }
    /**
     * @lrd:start
     * 病院情報を更新
     * @lrd:end
     */
    public function update(StoreHospitalRequest $request)
    {
        $success = $this->updateHospitalInfoByAuthStaffUseCase->update(
            $request->name,
            $request->zipcode,
            $request->address,
            $request->phone,
            $request->is_published,
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
