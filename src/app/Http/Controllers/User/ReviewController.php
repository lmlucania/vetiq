<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\ReviewService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreReviewRequest;
use App\Models\Review;
use App\Transformers\ReviewTransformer;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
