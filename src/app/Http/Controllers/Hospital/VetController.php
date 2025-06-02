<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\Menu\IndexVetUseCase;
use App\Application\UseCase\Hospital\Vet\DestroyVetUseCase;
use App\Application\UseCase\Hospital\Vet\ShowVetUseCase;
use App\Application\UseCase\Hospital\Vet\StoreVetUseCase;
use App\Application\UseCase\Hospital\Vet\UpdateVetUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\IndexVetRequest;
use App\Http\Requests\Hospital\StoreVetRequest;
use App\Http\Requests\Hospital\UpdateVetRequest;
use App\Transformers\VetTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class VetController extends Controller
{
    public function __construct(
        private readonly IndexVetUseCase $indexVetUseCase,
        private readonly StoreVetUseCase $storeVetUseCase,
        private readonly ShowVetUseCase $showVetUseCase,
        private readonly UpdateVetUseCase $updateVetUseCase,
        private readonly DestroyVetUseCase $destroyVetUseCase,
    ) {
    }

    /**
     * @lrd:start
     * 獣医師の一覧
     * @lrd:end
     */
    public function index(IndexVetRequest $request): JsonResponse
    {
        $paginatedDto = $this->indexVetUseCase->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginatedDto->getCollection(), new VetTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginatedDto->getPaginate()))
            ->respond();
    }

    /**
     * @lrd:start
     * 獣医師の登録
     * @lrd:end
     */
    public function store(StoreVetRequest $request)
    {
        $success = $this->storeVetUseCase->store(
            $request->getLastName(),
            $request->getFirstName(),
            $request->getAcceptAppointment(),
            $request->getRemark(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 獣医師の詳細
     * @lrd:end
     */
    public function show(Request $request, string $uuid):JsonResponse
    {
        $vetDto = $this->showVetUseCase->show($uuid);
        return fractal($vetDto, new VetTransformer())->respond();
    }

    /**
     * @lrd:start
     * 獣医師の更新
     * @lrd:end
     */
    public function update(UpdateVetRequest $request, string $uuid):JsonResponse
    {
        $success = $this->updateVetUseCase->update(
            uuid: $uuid,
            lastName: $request->getLastName(),
            firstName: $request->getFirstName(),
            acceptAppointment: $request->getAcceptAppointment(),
            remark: $request->getRemark(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 獣医師の削除
     * @lrd:end
     */
    public function destroy(Request $request, string $uuid): JsonResponse
    {
        $success = $this->destroyVetUseCase->destroy($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
