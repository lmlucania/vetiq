<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\UnauthorizedException;
use App\Models\Staff;

class AuthStaffService
{
    private Hospital $hospitalEntity;

    public function __construct(
        private readonly HospitalFactory $hospitalFactory,
    ) {
        $authUser = auth()->user();

        // ログインユーザーがスタッフ以外の時にエラーを返す
        if (! ($authUser instanceof Staff)) {
            throw new UnauthorizedException('スタッフ以外は許可されていません。');
        }

        $this->hospitalEntity = $this->hospitalFactory->modelToEntity($authUser->hospital);
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
