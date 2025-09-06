<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Dto\Request\ReviewImageDto;
use App\Application\Service\User\Review\CreateReviewService;
use App\Application\Service\User\Review\GetHospitalReviewsService;
use App\Application\Service\User\Review\GetMyReviewsService;
use App\Application\Service\User\Review\GetReviewDetailService;
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
        private GetHospitalReviewsService $getHospitalReviewsService,
        private CreateReviewService $createReviewService,
        private GetReviewDetailService $getReviewDetailService,
        private UpdateReviewService $updateReviewService,
        private GetMyReviewsService $getMyReviewsService,
    ) {
    }

    /**
     * @lrd:start
     * 指定する病院のレビュー一覧
     * @lrd:end
     */
    public function index(IndexReviewRequest $request, int $hospitalId)
    {
        $paginator = $this->getHospitalReviewsService->execute(
            hospitalId: $hospitalId,
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            rating: $request->getRating(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new ReviewTransformer())
            ->parseIncludes(['hospital', 'images'])
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * @lrd:start
     * レビューを登録
     * @lrd:end
     */
    public function store(StoreReviewRequest $request, int $hospitalId)
    {
        $success = $this->createReviewService->execute(
            hospitalId: $hospitalId,
            rating: $request->getRating(),
            title: $request->getTitle(),
            body: $request->getBody(),
            images: $request->getImages(),
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
    public function show(int $hospitalId, int $id)
    {
        $reviewWithImagesAndHospital = $this->getReviewDetailService->execute($hospitalId, $id);

        return fractal($reviewWithImagesAndHospital, new ReviewTransformer())
            ->parseIncludes(['hospital', 'images'])
            ->respond();
    }

    /**
     * @lrd:start
     * レビューの更新
     * @lrd:end
     */
    public function update(UpdateReviewRequest $request, int $hospitalId, int $id)
    {
        $dtos = [];
        foreach ($request->getImages() as $index => $image) {
            $dtos[] = new ReviewImageDto(
                id: isset($image['id']) ? (int)$image['id'] : null,
                file: $image['file'] ?? null,
                displayOrder: $index + 1,
            );
        }

        $success = $this->updateReviewService->execute(
            hospitalId: $hospitalId,
            id: $id,
            rating: $request->getRating(),
            title: $request->getTitle(),
            body: $request->getBody(),
            dtos: $dtos,
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
        $paginator = $this->getMyReviewsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            rating: $request->getRating(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new ReviewTransformer())
            ->parseIncludes(['hospital', 'images'])
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }
}
