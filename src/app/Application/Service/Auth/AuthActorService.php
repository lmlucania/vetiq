<?php

declare(strict_types=1);

namespace App\Application\Service\Auth;

use App\Exceptions\UnauthorizedException;
use App\Models\StaffModel;
use App\Models\User;
use Illuminate\Foundation\Auth\User as _User;

class AuthActorService
{
    private _User $authUser;

    public function __construct()
    {
        $this->authUser = auth()->user();
    }

    public function isStaff(): bool
    {
        return $this->authUser instanceof StaffModel;
    }

    public function isUser(): bool
    {
        return $this->authUser instanceof User;
    }

    public function getStaff(): StaffModel
    {
        if (! ($this->isStaff())) {
            throw new UnauthorizedException('スタッフ以外は許可されていません。');
        }

        return $this->authUser;
    }

    public function getUser(): User
    {
        if (! ($this->isUser())) {
            throw new UnauthorizedException('ユーザー以外は許可されていません。');
        }

        return $this->authUser;
    }

    public function getStaffId(): int
    {
        return $this->getStaff()->id;
    }

    public function getUserId(): int
    {
        return $this->getUser()->id;
    }

    public function getHospitalId(): int
    {
        return $this->getStaff()->hospital_id;
    }
}
