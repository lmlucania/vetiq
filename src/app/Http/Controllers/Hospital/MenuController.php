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
use App\Http\Requests\Hospital\StoreMenuRequest;
use App\Http\Requests\Hospital\UpdateMenuRequest;
use App\Transformers\MenuTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @lrd:start
     * 診察メニューの一覧
     * @lrd:end
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
     * @lrd:start
     * 診察メニューの登録
     * @lrd:end
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
     * @lrd:start
     * 診察メニューの詳細
     * @lrd:end
     */
    public function show(Request $request, string $uuid): JsonResponse
    {
        $menuDto = $this->showMenuUseCase->show($uuid);
        return fractal($menuDto, new MenuTransformer())->respond();
    }

    /**
     * @lrd:start
     * 診察メニューの更新
     * @lrd:end
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
     * @lrd:start
     * 診察メニューの削除
     * @lrd:end
     */
    public function destroy(Request $request, string $uuid): JsonResponse
    {
        $success = $this->destroyMenuUseCase->destroy($uuid);

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
