<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\BusinessHour\DeleteOwnBusinessHourService;
use App\Application\Service\Hospital\BusinessHour\GetOwnBusinessHoursService;
use App\Application\Service\Hospital\BusinessHour\SyncOwnBusinessHoursByDayOfWeekService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\BusinessHour\StoreBusinessHourRequest;
use App\Transformers\BusinessHourTransformer;
use Illuminate\Http\JsonResponse;

class BusinessHourController extends Controller
{
    public function __construct(
        private GetOwnBusinessHoursService $getOwnBusinessHoursService,
        private SyncOwnBusinessHoursByDayOfWeekService $syncOwnBusinessHoursByDayOfWeekService,
        private DeleteOwnBusinessHourService $deleteOwnBusinessHourService,
    ) {
    }

    /**
     * @lrd:start
     * 受付時間の一覧
     * @lrd:end
     */
    public function index()
    {
        $businessHours = $this->getOwnBusinessHoursService->execute();
        return fractal($businessHours, new BusinessHourTransformer())->respond();
    }

    /**
     * @lrd:start
     * 指定した曜日の受付時間の作成/更新
     * @lrd:end
     */
    public function store(StoreBusinessHourRequest $request)
    {
        $success = $this->syncOwnBusinessHoursByDayOfWeekService->execute($request->getDto());

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
    public function destroy(int $id): JsonResponse
    {
        $success = $this->deleteOwnBusinessHourService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
