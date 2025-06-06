<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\ReviewService;
use App\Http\Controllers\Controller;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @lrd:start
     * レビューの詳細
     * @lrd:end
     */
    public function show(string $hospitalUuid, string $reviewUuid)
    {
        $review = $this->reviewService->getOwnByUuidInHospital($hospitalUuid, $reviewUuid);

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
