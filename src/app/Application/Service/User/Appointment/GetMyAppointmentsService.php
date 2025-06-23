<?php

namespace App\Application\Service\User\Appointment;

use App\Application\Service\Auth\AuthActorService;

class GetMyAppointmentsService
{

    public function __construct(
        private AuthActorService $authActorService,
    )
    {
    }

    public function execute()
    {

    }
}
