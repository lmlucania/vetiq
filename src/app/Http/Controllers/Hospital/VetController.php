<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\UseCase\Hospital\IndexVetUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\IndexVetRequest;
use App\Transformers\VetTransformer;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class VetController extends Controller
{
    public function __construct(
        private readonly IndexVetUseCase $indexVetUseCase
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
}
