<?php

declare(strict_types=1);

namespace App\Application\Service;

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

    public function getUserId(): int
    {
        if (! ($this->isUser())) {
            throw new UnauthorizedException('ユーザー以外は許可されていません。');
        }

        return $this->authUser->id;
    }
}
