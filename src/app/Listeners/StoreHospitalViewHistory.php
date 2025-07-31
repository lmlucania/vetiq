<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Application\Service\User\HospitalViewHistory\CreateHospitalViewHistoryService;
use App\Events\HospitalViewed;

class StoreHospitalViewHistory
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private CreateHospitalViewHistoryService $createHospitalViewHistoryService,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HospitalViewed $event): void
    {
        $this->createHospitalViewHistoryService->execute(
            hospitalId: $event->hospitalId,
        );
    }
}
