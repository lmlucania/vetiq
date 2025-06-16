<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Notification\CreateNotificationService;
use App\Application\Service\Hospital\Notification\DeleteNotificationService;
use App\Application\Service\Hospital\Notification\GetOwnNotificationDetailService;
use App\Application\Service\Hospital\Notification\GetOwnNotificationsService;
use App\Application\Service\Hospital\Notification\UpdateNotificationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Notification\IndexNotificationRequest;
use App\Http\Requests\Hospital\Notification\StoreNotificationRequest;
use App\Http\Requests\Hospital\Notification\UpdateNotificationRequest;
use App\Models\Notification;
use App\Transformers\MenuTransformer;
use App\Transformers\NotificationTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class NotificationController extends Controller
{
    public function __construct(
        private GetOwnNotificationsService $getOwnNotificationsService,
        private CreateNotificationService $createNotificationService,
        private GetOwnNotificationDetailService $getOwnNotificationDetailService,
        private UpdateNotificationService $updateNotificationService,
        private DeleteNotificationService $deleteNotificationService,
    ) {
    }

    /**
     * @lrd:start
     * お知らせの一覧
     * @lrd:end
     */
    public function index(IndexNotificationRequest $request)
    {
        $paginator = $this->getOwnNotificationsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new NotificationTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
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
