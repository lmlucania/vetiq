<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital\Vet;

use App\Application\Service\VetService;

class DestroyVetUseCase
{
    public function __construct(
        private readonly VetService $vetService,
    ) {
    }

    public function destroy(string $uuid):bool
    {
        return $this->vetService->delete($uuid);
    }
}
