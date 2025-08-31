<?php

declare(strict_types=1);

namespace App\Application\Service\User\Appointment;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Domains\Appointment\Factory\AppointmentFactory;
use App\Domains\Appointment\Repositories\AppointmentImageAttachRepositoryInterface;
use App\Domains\Appointment\Repositories\AppointmentImageStorageRepositoryInterface;
use App\Domains\Appointment\Repositories\AppointmentRepositoryInterface;
use App\Domains\Appointment\Repositories\AppointmentStatusHistoryRepositoryInterface;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Exceptions\DomainException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateAppointmentService
{
    public function __construct(
        private AuthActorService $authActorService,
        private AppointmentRepositoryInterface $appointmentRepository,
        private AppointmentStatusHistoryRepositoryInterface $statusHistoryRepository,
        private PetRepositoryInterface $petRepository,
        private HospitalRepositoryInterface $hospitalRepository,
        private MenuRepositoryInterface $menuRepository,
        private VetRepositoryInterface $vetRepository,
        private AppointmentFactory $appointmentFactory,
        private AppointmentImageStorageRepositoryInterface $appointmentImageStorageRepository,
        private AppointmentImageAttachRepositoryInterface $appointmentImageAttachRepository,
    ) {
    }

    public function execute(
        int $petId,
        int $hospitalId,
        int $menuId,
        ?int $vetId,
        Carbon $appointmentAt,
        ?array $images,
    ): bool {
        $pet = $this->petRepository->getByUserIdAndId(
            userId: $this->authActorService->getUserId(),
            id: $petId,
        );
        $hospital = $this->hospitalRepository->getById($hospitalId);
        $menu     = $this->menuRepository->getByHospitalIdAndId(
            hospitalId: $hospital->id,
            id: $menuId,
        );
        $vet = is_null($vetId)
            ? null
            : $this->vetRepository->getByHospitalIdAndId(hospitalId: $hospital->id, id: $vetId);

        $appointmentEntity = $this->appointmentFactory->newEntityFromPrimitives(
            petId: $pet->id,
            hospitalId: $hospital->id,
            menuId: $menu->id,
            vetId: $vet?->id,
            appointmentAt: $appointmentAt,
            status: AppointmentStatus::Reserved,
            hospitalMemo: null,
        );

        try {
            $appointmentEntityWithId = DB::transaction(function () use ($appointmentEntity) {
                $appointmentEntityWithId = $this->appointmentRepository->create($appointmentEntity);

                $this->statusHistoryRepository->create(
                    appointmentEntity: $appointmentEntityWithId,
                    modifier: $this->authActorService->getUser(),
                );

                return $appointmentEntityWithId;
            });
        } catch (DomainException $e) {
            // ドメインの例外は、そのまま上に投げる
            throw $e;
        } catch (Throwable $e) {
            Log::error('Appointment create failed', ['error' => $e]);
            return false;
        }

        // 途中でエラーにならない場合はここまで到達する
        if (empty($images)) {
            return true;
        }

        $s3Paths = $this->appointmentImageStorageRepository->saveMany(
            appointmentId: $appointmentEntityWithId->getAppointmentId()->getValue(),
            images: $images,
        );

        return $this->appointmentImageAttachRepository->attach(
            appointmentId: $appointmentEntityWithId->getAppointmentId()->getValue(),
            paths: $s3Paths,
        );
    }
}
