<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Menu\CreateMenuService;
use App\Application\Service\Menu\DeleteMenuService;
use App\Application\Service\Menu\GetMenuDetailService;
use App\Application\Service\Menu\ListHospitalMenusService;
use App\Application\Service\Menu\SwitchPublishMenuService;
use App\Application\Service\Menu\SwitchUnPublishMenuService;
use App\Application\Service\Menu\UpdateMenuService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\IndexMenuRequest;
use App\Http\Requests\Hospital\StoreMenuRequest;
use App\Http\Requests\Hospital\UpdateMenuRequest;
use App\Transformers\MenuTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class MenuController extends Controller
{
    public function __construct(
        private ListHospitalMenusService $hospitalMenusService,
        private CreateMenuService $createMenuService,
        private GetMenuDetailService $getMenuDetailService,
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
        $paginator = $this->hospitalMenusService->execute(
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
        $menu = $this->getMenuDetailService->execute($id);
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
    public function publish(Request $request, string $uuid): JsonResponse
    {
        $success = $this->publishMenuUseCase->publish($uuid);

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
    public function unpublish(Request $request, string $uuid): JsonResponse
    {
        $success = $this->unpublishMenuUseCase->unpublish($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
