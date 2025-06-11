<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\Favorite\AttachFavoriteService;
use App\Application\Service\User\Favorite\DetachFavoriteService;
use App\Application\Service\User\Favorite\GetMyFavoriteHospitalsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Favorite\IndexFavoriteRequest;
use App\Transformers\FavoriteTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class FavoriteController extends Controller
{
    public function __construct(
        private GetMyFavoriteHospitalsService $getMyFavoriteHospitalsService,
        private AttachFavoriteService $attachFavoriteService,
        private DetachFavoriteService $detachFavoriteService,
    ) {
    }

    /**
     * @lrd:start
     * お気に入り一覧
     * @lrd:end
     */
    public function index(IndexFavoriteRequest $request)
    {
        $paginator = $this->getMyFavoriteHospitalsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );
        return fractal($paginator->getCollection(), new FavoriteTransformer())
            ->parseIncludes(['hospital'])
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * @lrd:start
     * お気に入り登録
     * @lrd:end
     */
    public function attach(string $uuid)
    {
        $success = $this->attachFavoriteService->execute($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * お気に入り解除
     * @lrd:end
     */
    public function detach(string $uuid)
    {
        $this->detachFavoriteService->execute($uuid);

        // お気に入りしていない病院を解除した場合でも200を返す
        return response()->success();
    }
}
