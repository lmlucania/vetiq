<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Vet;

use App\Application\Service\VetService;

class StoreVetUseCase
{
    public function __construct(
        private readonly VetService $vetService,
    ) {
    }

    public function store(string $lastName, string $firstName, bool $acceptAppointment, string $remark):bool
    {
        return $this->vetService->store($lastName, $firstName, $acceptAppointment, $remark);
    }
}
