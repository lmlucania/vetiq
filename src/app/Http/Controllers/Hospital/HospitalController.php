<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Domains\Hospital\Service\HospitalService;
use App\Http\Controllers\Controller;
use App\Transformers\HospitalTransformer;

class HospitalController extends Controller
{
    public function __construct(
        private readonly HospitalService $hospitalService
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/hospital/me",
     *     tags={"Hospital"},
     *     summary="病院情報",
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *     ),
     * )
     */
    public function show() {
        $dto = $this->hospitalService->getByStaff();
        return fractal($dto, new HospitalTransformer())->respond();
    }
}
