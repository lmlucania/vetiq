<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\DestroyVetUseCase;
use App\Application\UseCase\Hospital\IndexVetUseCase;
use App\Application\UseCase\Hospital\ShowVetUseCase;
use App\Application\UseCase\Hospital\StoreVetUseCase;
use App\Application\UseCase\Hospital\UpdateVetUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\IndexVetRequest;
use App\Http\Requests\Hospital\QueryParamVetRequest;
use App\Http\Requests\Hospital\StoreVetRequest;
use App\Http\Requests\Hospital\UpdateVetRequest;
use App\Transformers\VetTransformer;
use Illuminate\Http\JsonResponse;
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
     * @OA\Get(
     *     path="/hospital/vets",
     *     tags={"Hospital"},
     *     summary="獣医師の一覧",
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="ページ番号"
     *     ),
     *     @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="1ページあたりの表示数（デフォルト50件）"
     *     ),
     *     @OA\Parameter(
     *          name="sort[]",
     *          in="query",
     *          description="並び替え",
     *          style="deepObject",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              items=@OA\Items(type="string"),
     *              example={"last_name", "-accept_appointment"}
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          description="検索キーワード（名前（姓）または名前（名）の部分一致）"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Response~1Vet"
     *          ),
     *     ),
     * )
     */
    public function index(IndexVetRequest $request): JsonResponse
    {
        $paginatedDto = $this->indexVetUseCase->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
        );

        return fractal($paginatedDto->getCollection(), new VetTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginatedDto->getPaginate()))
            ->respond();
    }

    /**
     * @OA\Post(
     *     path="/hospital/vets",
     *     tags={"Hospital"},
     *     summary="獣医師の登録",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1StoreVetRequest")
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
     * @OA\Get(
     *     path="/hospital/vets/{uuid}",
     *     tags={"Hospital"},
     *     summary="獣医師の詳細",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="獣医師ID",
     *          example="5705d9e7-39bd-44c4-af36-be78d0eeb825",
     *      ),
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Response~1Vet"
     *          ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *     ),
     * )
     */
    public function show(QueryParamVetRequest $request, string $uuid):JsonResponse
    {
        $vetDto = $this->showVetUseCase->show($uuid);
        return fractal($vetDto, new VetTransformer())->respond();
    }

    /**
     * @OA\Put(
     *     path="/hospital/vets/{uuid}",
     *     tags={"Hospital"},
     *     summary="獣医師の登録",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="獣医師ID",
     *          example="5705d9e7-39bd-44c4-af36-be78d0eeb825",
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1UpdateVetRequest")
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
    public function update(UpdateVetRequest $request, string $uuid):JsonResponse
    {
        // fixme テスト未実施
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
     * @OA\Delete(
     *     path="/hospital/vets/{uuid}",
     *     tags={"Hospital"},
     *     summary="獣医師の削除",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="獣医師ID",
     *          example="5705d9e7-39bd-44c4-af36-be78d0eeb825",
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
    public function destroy(QueryParamVetRequest $request, string $uuid): JsonResponse
    {
        $success = $this->destroyVetUseCase->destroy($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
