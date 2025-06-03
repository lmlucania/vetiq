<?php

declare(strict_types=1);

namespace App\Domains\User\Repository;

interface UserProfileRepositoryInterface
{
    public function upsert(
        int $userId,
        string $firstName,
        string $lastName,
        ?string $firstNameKana,
        ?string $lastNameKana,
        string $phoneNumber,
        ?string $postCode,
        ?int $prefecture,
        ?string $address1,
        ?string $address2
    ): int;
}
