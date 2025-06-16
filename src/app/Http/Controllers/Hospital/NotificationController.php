<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Notification\CreateNotificationService;
use App\Application\Service\Hospital\Notification\UpdateNotificationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Notification\StoreNotificationRequest;
use App\Http\Requests\Hospital\Notification\UpdateNotificationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private CreateNotificationService $createNotificationService,
        private UpdateNotificationService $updateNotificationService,
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
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
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
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
