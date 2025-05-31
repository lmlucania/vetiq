<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\ExceptionHour\DestroyExceptionHourUseCase;
use App\Application\UseCase\Hospital\ExceptionHour\IndexExceptionHourUseCase;
use App\Application\UseCase\Hospital\ExceptionHour\StoreExceptionHourUseCase;
use App\Application\UseCase\Hospital\ExceptionHour\UpdateExceptionHourUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\IndexExceptionHourRequest;
use App\Transformers\ExceptionHourTransformer;
use Illuminate\Http\Request;

class ExceptionHourController extends Controller
{
    public function __construct(
        private IndexExceptionHourUseCase $indexExceptionHourUseCase,
        private StoreExceptionHourUseCase $storeExceptionHourUseCase,
        private UpdateExceptionHourUseCase $updateExceptionHourUseCase,
        private DestroyExceptionHourUseCase $destroyExceptionHourUseCase,
    ) {
    }

    /**
     * @lrd:start
     * 例外受付時間の一覧
     * @lrd:end
     */
    public function index(IndexExceptionHourRequest $indexExceptionHourRequest, int $year)
    {
        $dto = $this->indexExceptionHourUseCase->execute($year);
        return fractal($dto->getCollection(), new ExceptionHourTransformer());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
