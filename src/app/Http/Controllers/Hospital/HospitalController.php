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
     * @OA\Get(
     *     path="/hospital/info",
     *     tags={"Hospital"},
     *     summary="病院情報を取得",
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Response~1Hospital"
     *          ),
     *     ),
     * )
     */
    public function show()
    {
        $dto = $this->showHospitalInfoByAuthStaffUseCase->show();
        return fractal($dto, new HospitalTransformer())->respond();
    }

    /**
     * @OA\Put(
     *     path="/hospital/info",
     *     tags={"Hospital"},
     *     summary="病院情報を更新",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1StoreHospitalRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="バリデーションエラー",
     *     ),
     * )
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
