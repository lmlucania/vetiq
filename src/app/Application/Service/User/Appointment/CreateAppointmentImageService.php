<?php

declare(strict_types=1);

namespace App\Application\Service\User\Appointment;

use App\Domains\Appointment\Repositories\AppointmentImageAttachRepositoryInterface;
use App\Domains\Appointment\Repositories\AppointmentImageStorageRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 予約画像を保存するサービスクラス
 */
class CreateAppointmentImageService
{
    public function __construct(
        private AppointmentImageStorageRepositoryInterface $appointmentImageStorageRepository,
        private AppointmentImageAttachRepositoryInterface $appointmentImageAttachRepository,
    ) {
    }

    /**
     * @param int $appointmentId
     * @param array $images
     * @return bool
     */
    public function execute(int $appointmentId, array $images): bool
    {
        if (empty($images)) {
            return true;
        }

        $insertRows = [];
        foreach ($images as $index => $image) {
            $now    = Carbon::now();
            $s3Path = $this->appointmentImageStorageRepository->save($appointmentId, $image);

            $insertRows[] = [
                'appointment_id' => $appointmentId,
                'image_path'     => $s3Path,
                'display_order'  => $index + 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        try {
            return $this->appointmentImageAttachRepository->attachMany($appointmentId, $insertRows);
        } catch (Throwable $e) {
            Log::error('Fail to create appointment image', ['error' => $e]);
            $this->appointmentImageStorageRepository->deleteMany(array_column($insertRows, 'image_path'));
            return false;
        }
    }
}
