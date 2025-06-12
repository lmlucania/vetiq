<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\ExceptionHour\DeleteOwnExceptionHourService;
use App\Application\Service\Hospital\ExceptionHour\GetOwnExceptionHoursYearlyService;
use App\Application\Service\Hospital\ExceptionHour\SyncOwnExceptionHoursByDateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\ExceptionHour\IndexExceptionHourRequest;
use App\Http\Requests\Hospital\ExceptionHour\StoreExceptionHourRequest;
use App\Transformers\ExceptionHourTransformer;

class ExceptionHourController extends Controller
{
    public function __construct(
        private GetOwnExceptionHoursYearlyService $getOwnExceptionHoursYearlyService,
        private SyncOwnExceptionHoursByDateService $syncOwnExceptionHoursByDateService,
        private DeleteOwnExceptionHourService $deleteOwnExceptionHourService,
    ) {
    }

    /**
     * @lrd:start
     * 例外受付時間の一覧
     * @lrd:end
     */
    public function index(IndexExceptionHourRequest $indexExceptionHourRequest, int $year)
    {
        $exceptionHours = $this->getOwnExceptionHoursYearlyService->execute($year);
        return fractal($exceptionHours, new ExceptionHourTransformer());
    }

    /**
     * @lrd:start
     * 指定した日付の例外受付時間の作成/更新
     * @lrd:end
     */
    public function store(StoreExceptionHourRequest $request)
    {
        $success = $this->syncOwnExceptionHoursByDateService->execute($request->getDto());

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 例外受付時間の削除
     * @lrd:end
     */
    public function destroy(int $id)
    {
        $success = $this->deleteOwnExceptionHourService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
