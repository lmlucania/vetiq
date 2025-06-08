<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\ReviewService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexReviewRequest;
use App\Http\Requests\User\StoreReviewRequest;
use App\Infrastructure\QueryService\ReviewQueryServiceInterface;
use App\Models\Review;
use App\Transformers\ReviewTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService,
        private ReviewQueryServiceInterface $reviewQueryService,
    ) {
    }

    /**
     * @lrd:start
     * 病院のレビュー一覧
     * @lrd:end
     */
    public function index(IndexReviewRequest $request, string $hospitalUuid)
    {
        $paginator = $this->reviewQueryService->listByCriteria(
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
        $success = $this->reviewService->create(
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
        $review = $this->reviewService->getOwnByUuidInHospital($hospitalUuid, $uuid);

        return fractal($review, new ReviewTransformer())->respond();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
