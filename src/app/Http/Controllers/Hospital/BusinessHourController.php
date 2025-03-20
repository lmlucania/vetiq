<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\DestroyBusinessHourUseCase;
use App\Application\UseCase\Hospital\IndexBusinessHourUseCase;
use App\Application\UseCase\Hospital\StoreBusinessHourUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\QueryParamBusinessHourRequest;
use App\Http\Requests\Hospital\StoreBusinessHourRequest;
use App\Transformers\BusinessHourTransformer;
use Illuminate\Http\JsonResponse;

class BusinessHourController extends Controller
{
    public function __construct(
        private readonly IndexBusinessHourUseCase $indexBusinessHourUseCaseUseCase,
        private readonly StoreBusinessHourUseCase $storeBusinessHourUseCase,
        private readonly DestroyBusinessHourUseCase $destroyBusinessHourUseCase,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/hospital/business_hours",
     *     tags={"Hospital"},
     *     summary="受付時間の一覧",
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Response~1BusinessHour"
     *          ),
     *     ),
     * )
     */
    public function index()
    {
        $dto = $this->indexBusinessHourUseCaseUseCase->execute();
        return fractal($dto->getCollection(), new BusinessHourTransformer())->respond();
    }

    /**
     * @OA\Post(
     *     path="/hospital/business_hours",
     *     tags={"Hospital"},
     *     summary="指定した曜日の受付時間の作成/更新",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1StoreBusinessHourRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="失敗",
     *     )
     * )
     */
    public function store(StoreBusinessHourRequest $request)
    {
        $success = $this->storeBusinessHourUseCase->execute(
            dayOfWeek: $request->day_of_week,
            periods: $request->periods,
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @OA\Delete(
     *     path="/hospital/business_hours/{uuid}",
     *     tags={"Hospital"},
     *     summary="予約受付時間の削除",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="受付時間ID",
     *          example="1667cff9-71e5-4719-953c-e074507d2d3d",
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="成功",
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="失敗",
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Not Found",
     *      ),
     * )
     */
    public function destroy(QueryParamBusinessHourRequest $request, string $uuid): JsonResponse
    {
        $success = $this->destroyBusinessHourUseCase->execute($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
