<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Tag\GetOwnTagsService;
use App\Application\Service\Hospital\Tag\SyncTagService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Tag\SyncTagRequest;
use App\Transformers\CategoryTagTransformer;
use App\Transformers\TagTransformer;

class TagController extends Controller
{
    public function __construct(
        private GetOwnTagsService $getOwnTagsService,
        private SyncTagService $syncTagService
    ) {
    }

    /**
     * @lrd:start
     * 病院に紐づいているタグの一覧
     * @lrd:end
     */
    public function index()
    {
        $tags = $this->getOwnTagsService->execute();
        return fractal($tags, new CategoryTagTransformer())->respond();
    }

    /**
     * @lrd:start
     * 病院にタグを紐づける
     * @lrd:end
     */
    public function sync(SyncTagRequest $request)
    {
        $success = $this->syncTagService->execute($request->getIds());

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
