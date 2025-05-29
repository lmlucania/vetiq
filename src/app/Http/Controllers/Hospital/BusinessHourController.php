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
     * @lrd:start
     * 受付時間の一覧
     * @lrd:end
     */
    public function index()
    {
        $dto = $this->indexBusinessHourUseCaseUseCase->execute();
        return fractal($dto->getCollection(), new BusinessHourTransformer())->respond();
    }

    /**
     * @lrd:start
     * 指定した曜日の受付時間の作成/更新
     * @lrd:end
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
     * @lrd:start
     * 予約受付時間の削除
     * @lrd:end
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
