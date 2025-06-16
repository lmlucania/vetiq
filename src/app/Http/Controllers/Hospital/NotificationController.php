<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Service\Hospital\Notification\CreateNotificationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Notification\StoreNotificationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private CreateNotificationService $createNotificationService,
    )
    {
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
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
