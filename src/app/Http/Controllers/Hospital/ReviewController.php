<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Review\GetOwnReviewsService;
use App\Http\Requests\Hospital\Review\IndexReviewRequest;
use App\Transformers\ReviewTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ReviewController
{
    public function __construct(
        private GetOwnReviewsService $getOwnReviewsService,
    ) {
    }

    /**
     * @lrd:start
     * 病院のレビュー一覧
     * @lrd:end
     */
    public function index(IndexReviewRequest $request)
    {
        $paginator = $this->getOwnReviewsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            rating: $request->getRating(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new ReviewTransformer())
            ->parseIncludes(['images'])
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }
}
