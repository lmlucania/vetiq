<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\FavoriteService;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    public function __construct(
        private FavoriteService $favoriteService,
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
     * お気に入り登録
     * @lrd:end
     */
    public function attach(string $uuid)
    {
        $success = $this->favoriteService->attach($uuid);

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
        $success = $this->favoriteService->detach($uuid);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
