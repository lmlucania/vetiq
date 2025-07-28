<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Tag\SyncTagService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Tag\SyncTagRequest;

class TagController extends Controller
{
    public function __construct(
        private SyncTagService $syncTagService
    ) {
    }

    public function sync(SyncTagRequest $request)
    {
        $success = $this->syncTagService->execute($request->getIds());

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
