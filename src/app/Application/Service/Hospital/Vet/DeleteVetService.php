<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\Vet;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\Vet\DomainService\VetDomainService;
use App\Domains\Vet\Repository\VetRepositoryInterface;
use App\Exceptions\DomainException;

class DeleteVetService
{
    public function __construct(
        private VetRepositoryInterface $vetRepository,
        private VetDomainService $vetDomainService,
        private AuthActorService $authActorService,
    ) {
    }

    public function execute(int $id): bool
    {
        $hospitalId = $this->authActorService->getHospitalId();

        // ビジネスロジックの検証の前に、指定された獣医が存在することを確認する
        $vet = $this->vetRepository->getByHospitalIdAndId(
            hospitalId: $hospitalId,
            id: $id,
        );

        if (!$this->vetDomainService->canDelete($hospitalId)) {
            throw new DomainException('獣医が1人しかいないため削除できません。');
        }

        return $this->vetRepository->delete($vet->id);
    }
}
