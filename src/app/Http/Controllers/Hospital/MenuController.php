<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\IndexMenuUseCase;
use App\Application\UseCase\Hospital\ShowMenuUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\IndexMenuRequest;
use App\Http\Requests\Hospital\ShowMenuRequest;
use App\Transformers\MenuTransformer;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class MenuController extends Controller
{
    public function __construct(
        private readonly ShowMenuUseCase $showMenuUseCase,
        private readonly IndexMenuUseCase $indexMenuUseCase
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
     *              ref="#/components/schemas/Response~1Hospital"
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
        );

        return fractal($paginatedDto->getCollection(), new MenuTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginatedDto->getPaginate()))
            ->respond();
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
     * )
     */
    public function show(ShowMenuRequest $request): JsonResponse
    {
        $menuDto = $this->showMenuUseCase->show($request->menu);
        return fractal($menuDto, new MenuTransformer())->respond();
    }
}
