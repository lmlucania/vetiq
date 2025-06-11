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

    public function index(IndexReviewRequest $request, string $hospitalUuid)
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
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }
}
