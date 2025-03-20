<?php

declare(strict_types=1);

namespace App\Application\UseCase\Hospital;

use App\Application\Dto\Response\VetDto;
use App\Application\Service\VetService;
use App\Domains\Vet\Factory\VetFactory;

class ShowVetUseCase
{
    public function __construct(
        private readonly VetService $vetService,
        private readonly VetFactory $vetFactory,
    ) {
    }

    public function show(string $uuid): VetDto
    {
        $vetEntity = $this->vetService->getHospitalOwnByUuid($uuid);
        return $this->vetFactory->entityToDto($vetEntity);
    }
}
