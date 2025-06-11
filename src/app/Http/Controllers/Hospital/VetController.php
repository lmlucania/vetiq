<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Vet\CreateVetService;
use App\Application\Service\Hospital\Vet\DeleteVetService;
use App\Application\Service\Hospital\Vet\GetOwnVetDetailService;
use App\Application\Service\Hospital\Vet\GetOwnVetsService;
use App\Application\Service\Hospital\Vet\UpdateVetService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Vet\IndexVetRequest;
use App\Http\Requests\Hospital\Vet\StoreVetRequest;
use App\Http\Requests\Hospital\Vet\UpdateVetRequest;
use App\Transformers\VetTransformer;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class VetController extends Controller
{
    public function __construct(
        private GetOwnVetsService $getOwnVetsService,
        private CreateVetService $createVetService,
        private GetOwnVetDetailService $getOwnVetDetailService,
        private UpdateVetService $updateVetService,
        private DeleteVetService $deleteVetService,
    ) {
    }

    /**
     * @lrd:start
     * 獣医師の一覧
     * @lrd:end
     */
    public function index(IndexVetRequest $request): JsonResponse
    {
        $paginator = $this->getOwnVetsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new VetTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * @lrd:start
     * 獣医師の登録
     * @lrd:end
     */
    public function store(StoreVetRequest $request)
    {
        $success = $this->createVetService->execute(
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
    public function show(int $id):JsonResponse
    {
        $vet = $this->getOwnVetDetailService->execute($id);
        return fractal($vet, new VetTransformer())->respond();
    }

    /**
     * @lrd:start
     * 獣医師の更新
     * @lrd:end
     */
    public function update(UpdateVetRequest $request, int $id):JsonResponse
    {
        $success = $this->updateVetService->execute(
            id: $id,
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
    public function destroy(int $id): JsonResponse
    {
        $success = $this->deleteVetService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
