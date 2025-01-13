<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Hospital\ValueObjects\HospitalId;

class AuthStaffService
{
    private Hospital $hospitalEntity;

    public function __construct(
        private readonly HospitalFactory $hospitalFactory,
    ) {
        $hospitalModel = auth()->user()->hospital;

        $this->hospitalEntity = $this->hospitalFactory->modelToEntity($hospitalModel);
    }

    /**
     * ログインスタッフの病院IDを取得する
     * @return HospitalId
     */
    public function getHospitalId():HospitalId
    {
        return $this->hospitalEntity->getId();
    }
}
