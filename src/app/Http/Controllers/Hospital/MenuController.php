<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Menu\CreateMenuService;
use App\Application\Service\Hospital\Menu\DeleteMenuService;
use App\Application\Service\Hospital\Menu\GetOwnMenuDetailService;
use App\Application\Service\Hospital\Menu\GetOwnMenusService;
use App\Application\Service\Hospital\Menu\SwitchPublishMenuService;
use App\Application\Service\Hospital\Menu\SwitchUnPublishMenuService;
use App\Application\Service\Hospital\Menu\UpdateMenuService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Menu\IndexMenuRequest;
use App\Http\Requests\Hospital\Menu\StoreMenuRequest;
use App\Http\Requests\Hospital\Menu\UpdateMenuRequest;
use App\Transformers\MenuTransformer;
use Illuminate\Http\JsonResponse;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class MenuController extends Controller
{
    public function __construct(
        private GetOwnMenusService $getOwnMenusService,
        private CreateMenuService $createMenuService,
        private GetOwnMenuDetailService $getOwnMenuDetailService,
        private UpdateMenuService $updateMenuService,
        private DeleteMenuService $deleteMenuService,
        private SwitchPublishMenuService $switchPublishMenuService,
        private SwitchUnPublishMenuService $switchUnPublishMenuService,
    ) {
    }

    /**
     * @lrd:start
     * 診察メニューの一覧
     * @lrd:end
     */
    public function index(IndexMenuRequest $request): JsonResponse
    {
        $paginator = $this->getOwnMenusService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new MenuTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * @lrd:start
     * 診察メニューの登録
     * @lrd:end
     */
    public function store(StoreMenuRequest $request): JsonResponse
    {
        $success = $this->createMenuService->execute(
            $request->getName(),
            $request->getDetail(),
            $request->getRequiredTime(),
            $request->isPublished(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 診察メニューの詳細
     * @lrd:end
     */
    public function show(int $id): JsonResponse
    {
        $menu = $this->getOwnMenuDetailService->execute($id);
        return fractal($menu, new MenuTransformer())->respond();
    }

    /**
     * @lrd:start
     * 診察メニューの更新
     * @lrd:end
     */
    public function update(UpdateMenuRequest $request, int $id): JsonResponse
    {
        $success = $this->updateMenuService->execute(
            $id,
            $request->getName(),
            $request->getDetail(),
            $request->getRequiredTime(),
            $request->isPublished(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 診察メニューの削除
     * @lrd:end
     */
    public function destroy(int $id): JsonResponse
    {
        $success = $this->deleteMenuService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 診察メニューを公開に変更
     * @lrd:end
     */
    public function publish(int $id): JsonResponse
    {
        $success = $this->switchPublishMenuService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 診察メニューを非公開に変更
     * @lrd:end
     */
    public function unpublish(int $id): JsonResponse
    {
        $success = $this->switchUnPublishMenuService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
