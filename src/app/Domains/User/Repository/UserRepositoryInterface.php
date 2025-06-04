<?php

declare(strict_types=1);

namespace App\Domains\User\Repository;

interface UserRepositoryInterface
{
    public function updateEmail(int $id, string $email): bool;

    public function delete(int $id): bool;
}
