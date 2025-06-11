<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\Review\CreateReviewService;
use App\Application\Service\User\Review\GetReviewDetailService;
use App\Application\Service\User\Review\HospitalReviewsService;
use App\Application\Service\User\Review\MyReviewsService;
use App\Application\Service\User\Review\UpdateReviewService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Review\IndexOwnReviewRequest;
use App\Http\Requests\User\Review\IndexReviewRequest;
use App\Http\Requests\User\Review\StoreReviewRequest;
use App\Http\Requests\User\Review\UpdateReviewRequest;
use App\Transformers\ReviewTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ReviewController extends Controller
{
    public function __construct(
        private HospitalReviewsService $hospitalReviewsService,
        private CreateReviewService $createReviewService,
        private GetReviewDetailService $getReviewDetailService,
        private UpdateReviewService $updateReviewService,
        private MyReviewsService $myReviewsService,
    ) {
    }

    /**
     * @lrd:start
     * 病院のレビュー一覧
     * @lrd:end
     */
    public function index(IndexReviewRequest $request, string $hospitalUuid)
    {
        $paginator = $this->hospitalReviewsService->execute(
            hospitalUuid: $hospitalUuid,
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            rating: $request->getRating(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new ReviewTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * @lrd:start
     * レビューを登録
     * @lrd:end
     */
    public function store(StoreReviewRequest $request, string $hospitalUuid)
    {
        $success = $this->createReviewService->execute(
            hospitalUuid: $hospitalUuid,
            rating: $request->getRating(),
            title: $request->getTitle(),
            body: $request->getBody(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * レビューの詳細
     * @lrd:end
     */
    public function show(string $hospitalUuid, string $uuid)
    {
        $review = $this->getReviewDetailService->execute($hospitalUuid, $uuid);

        return fractal($review, new ReviewTransformer())->respond();
    }

    /**
     * @lrd:start
     * レビューの更新
     * @lrd:end
     */
    public function update(UpdateReviewRequest $request, string $hospitalUuid, string $uuid)
    {
        $success = $this->updateReviewService->execute(
            hospitalUuid: $hospitalUuid,
            uuid: $uuid,
            rating: $request->getRating(),
            title: $request->getTitle(),
            body: $request->getBody(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * ログインユーザーが投稿したレビューの一覧
     * @lrd:end
     */
    public function indexOwn(IndexOwnReviewRequest $request)
    {
        $paginator = $this->myReviewsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            rating: $request->getRating(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new ReviewTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }
}
