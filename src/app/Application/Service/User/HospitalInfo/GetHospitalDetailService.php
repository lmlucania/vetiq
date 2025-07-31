<?php

declare(strict_types=1);

namespace App\Application\Service\User\HospitalInfo;

use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Events\HospitalViewed;
use App\Models\Hospital;

class GetHospitalDetailService
{
    public function __construct(
        private HospitalRepositoryInterface $hospitalRepository,
    ) {
    }

    public function execute(int $hospitalId): Hospital
    {
        event(new HospitalViewed($hospitalId));

        return $this->hospitalRepository->getById($hospitalId);
    }
}
