<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\User\Repository\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function updateEmail(int $id, string $email): bool
    {
        $user        = User::findOrFail($id);
        $user->email = $email;

        return $user->save();
    }
}
