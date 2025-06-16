<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Notification\CreateNotificationService;
use App\Application\Service\Hospital\Notification\DeleteNotificationService;
use App\Application\Service\Hospital\Notification\UpdateNotificationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Notification\StoreNotificationRequest;
use App\Http\Requests\Hospital\Notification\UpdateNotificationRequest;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct(
        private CreateNotificationService $createNotificationService,
        private UpdateNotificationService $updateNotificationService,
        private DeleteNotificationService $deleteNotificationService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // fixme サービスの作成から続きを実装する
    }

    /**
     * @lrd:start
     * お知らせの登録
     * @lrd:end
     */
    public function store(StoreNotificationRequest $request)
    {
        $success = $this->createNotificationService->execute(
            title: $request->getTitle(),
            detail: $request->getDetail(),
            isPublished: $request->getIsPublished(),
            publishedAt: $request->getPublishedAt(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * お知らせの詳細
     * @lrd:end
     */
    public function show(int $id)
    {
        $notification = $this->getOwnNotificationDetailService->execute($id);
        return fractal($notification, new NotificationTransformer())->respond();
    }

    /**
     * @lrd:start
     * お知らせの更新
     * @lrd:end
     */
    public function update(UpdateNotificationRequest $request, int $id)
    {
        $success = $this->updateNotificationService->execute(
            id: $id,
            title: $request->getTitle(),
            detail: $request->getDetail(),
            isPublished: $request->getIsPublished(),
            publishedAt: $request->getPublishedAt(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * お知らせの削除
     * @lrd:end
     */
    public function destroy(int $id)
    {
        $success = $this->deleteNotificationService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
