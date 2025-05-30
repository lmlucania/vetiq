<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\ExceptionHour\DestroyExceptionHourUseCase;
use App\Application\UseCase\Hospital\ExceptionHour\IndexExceptionHourUseCase;
use App\Application\UseCase\Hospital\ExceptionHour\StoreExceptionHourUseCase;
use App\Application\UseCase\Hospital\ExceptionHour\UpdateExceptionHourUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExceptionHourController extends Controller
{
    public function __construct(
        IndexExceptionHourUseCase $indexExceptionHourUseCase,
        StoreExceptionHourUseCase $storeExceptionHourUseCase,
        UpdateExceptionHourUseCase $updateExceptionHourUseCase,
        DestroyExceptionHourUseCase $destroyExceptionHourUseCase,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 1;
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
