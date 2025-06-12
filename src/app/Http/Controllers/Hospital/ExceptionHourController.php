<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\ExceptionHour\GetOwnExceptionHoursYearlyService;
use App\Application\Service\Hospital\ExceptionHour\SyncOwnExceptionHoursByDateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\ExceptionHour\IndexExceptionHourRequest;
use App\Http\Requests\Hospital\ExceptionHour\StoreExceptionHourRequest;
use App\Transformers\ExceptionHourTransformer;
use Illuminate\Http\Request;

class ExceptionHourController extends Controller
{
    public function __construct(
        private GetOwnExceptionHoursYearlyService $getOwnExceptionHoursYearlyService,
        private SyncOwnExceptionHoursByDateService $syncOwnExceptionHoursByDateService,
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
        // fixme 動作確認OK DBのunique indexをhospital_id, time_period, dateにする
        $success = $this->syncOwnExceptionHoursByDateService->execute($request->getDto());

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
