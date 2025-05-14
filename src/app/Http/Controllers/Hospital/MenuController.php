<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\DestroyMenuUseCase;
use App\Application\UseCase\Hospital\IndexMenuUseCase;
use App\Application\UseCase\Hospital\PublishMenuUseCase;
use App\Application\UseCase\Hospital\ShowMenuUseCase;
use App\Application\UseCase\Hospital\StoreMenuUseCase;
use App\Application\UseCase\Hospital\UnpublishMenuUseCase;
use App\Application\UseCase\Hospital\UpdateMenuUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\IndexMenuRequest;
use App\Http\Requests\Hospital\QueryParamMenuRequest;
use App\Http\Requests\Hospital\StoreMenuRequest;
use App\Http\Requests\Hospital\UpdateMenuRequest;
use App\Transformers\MenuTransformer;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class MenuController extends Controller
{
    public function __construct(
        private readonly IndexMenuUseCase $indexMenuUseCase,
        private readonly StoreMenuUseCase $storeMenuUseCase,
        private readonly ShowMenuUseCase $showMenuUseCase,
        private readonly UpdateMenuUseCase $updateMenuUseCase,
        private readonly DestroyMenuUseCase $destroyMenuUseCase,
        private readonly PublishMenuUseCase $publishMenuUseCase,
        private readonly UnpublishMenuUseCase $unpublishMenuUseCase,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/hospital/menus",
     *     tags={"Hospital"},
     *     summary="診察メニューの一覧",
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
     *              example={"name", "-detail"}
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          description="検索キーワード（メニュー名または説明の部分一致）"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Response~1Menu"
     *          ),
     *     ),
     * )
     */
    public function index(IndexMenuRequest $request): JsonResponse
    {
        $paginatedDto = $this->indexMenuUseCase->index(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginatedDto->getCollection(), new MenuTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginatedDto->getPaginate()))
            ->respond();
    }

    /**
     * @OA\Post(
     *     path="/hospital/menus",
     *     tags={"Hospital"},
     *     summary="診察メニューの登録",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1StoreMenuRequest")
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
    public function store(StoreMenuRequest $request)
    {
        $success = $this->storeMenuUseCase->store(
            $request->name,
            $request->detail,
            $request->required_time,
            $request->is_published,
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @OA\Get(
     *     path="/hospital/menus/{uuid}",
     *     tags={"Hospital"},
     *     summary="診察メニューの詳細",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="診察メニューID",
     *          example="1667cff9-71e5-4719-953c-e074507d2d3d",
     *      ),
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Response~1Menu"
     *          ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *     ),
     * )
     */
    public function show(QueryParamMenuRequest $request, string $uuid): JsonResponse
    {
        $menuDto = $this->showMenuUseCase->show($uuid);
        return fractal($menuDto, new MenuTransformer())->respond();
    }

    /**
     * @OA\Put(
     *     path="/hospital/menus/{uuid}",
     *     tags={"Hospital"},
     *     summary="診察メニューの更新",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="診察メニューID",
     *          example="1667cff9-71e5-4719-953c-e074507d2d3d",
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1UpdateMenuRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="成功",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="失敗",
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *     ),
     * )
     */
    public function update(UpdateMenuRequest $request, string $uuid): JsonResponse
    {
        $success = $this->updateMenuUseCase->update(
            $uuid,
            $request->name,
            $request->detail,
            $request->required_time,
            $request->is_published,
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @OA\Delete(
     *     path="/hospital/menus/{uuid}",
     *     tags={"Hospital"},
     *     summary="診察メニューの削除",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="診察メニューID",
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
    public function destroy(QueryParamMenuRequest $request, string $uuid): JsonResponse
    {
        $success = $this->destroyMenuUseCase->destroy($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @OA\Post(
     *     path="/hospital/menus/{uuid}/publish",
     *     tags={"Hospital"},
     *     summary="診察メニューを公開に変更",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="診察メニューID",
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
    public function publish(QueryParamMenuRequest $request, string $uuid): JsonResponse
    {
        $success = $this->publishMenuUseCase->publish($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @OA\Post(
     *     path="/hospital/menus/{uuid}/unpublish",
     *     tags={"Hospital"},
     *     summary="診察メニューを非公開に変更",
     *     @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="診察メニューID",
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
    public function unpublish(QueryParamMenuRequest $request, string $uuid): JsonResponse
    {
        $success = $this->unpublishMenuUseCase->unpublish($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
