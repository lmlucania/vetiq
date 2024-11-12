<?php

declare(strict_types=1);

namespace App\Providers;

use \Symfony\Component\HttpFoundation\Response as ResponseStatus;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($message = 'リクエストが正常に完了しました。') {
            return response()->json([
                'status'  => 'success',
                'message' => $message,
            ]);
        });

        Response::macro('error', function ($message = 'リクエストの処理中にエラーが発生しました。', array $errors = [], $status = ResponseStatus::HTTP_BAD_REQUEST) {
            return response()->json([
                'status'  => 'error',
                'message' => $message,
                'errors'  => (object) $errors,
            ], $status);
        });

        Response::macro('fatalError', function ($message = 'システムエラーが発生しました。', array $errors = [], $status = ResponseStatus::HTTP_INTERNAL_SERVER_ERROR) {
            return response()->json([
                'status'  => 'error',
                'message' => $message,
                'errors'  => (object) $errors,
            ], $status);
        });

        Response::macro('maintenance', function () {
            return response()->json([
                'message' => 'Site is under maintenance.',
                'errors'  => (object) [],
            ], ResponseStatus::HTTP_SERVICE_UNAVAILABLE);
        });
    }
}
