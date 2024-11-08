<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Domains\Hospital\Service\HospitalService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\HospitalSaveRequest;
use App\Transformers\HospitalTransformer;

class HospitalController extends Controller
{
    public function __construct(
        private readonly HospitalService $hospitalService
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
        $dto = $this->hospitalService->getByStaff();
        return fractal($dto, new HospitalTransformer())->respond();
    }

    /**
     * @OA\Put(
     *     path="/hospital/info",
     *     tags={"Hospital"},
     *     summary="病院情報を更新",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1HospitalSaveRequest")
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
    public function update(HospitalSaveRequest $request)
    {
        $this->hospitalService->update(
            $request->name,
            $request->zipcode,
            $request->address,
            $request->phone,
            $request->is_published,
        );
        return response()->json();
    }
}
