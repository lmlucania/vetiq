<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Vet;

use App\Application\Service\VetService;

class UpdateVetUseCase
{
    public function __construct(
        private readonly VetService $vetService,
    ) {
    }

    public function update(string $uuid, string $lastName, string $firstName, bool $acceptAppointment, string $remark):bool
    {
        return $this->vetService->update($uuid, $lastName, $firstName, $acceptAppointment, $remark);
    }
}
