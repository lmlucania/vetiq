<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\User\Repository\UserProfileRepositoryInterface;
use App\Models\UserProfile;

class UserProfileRepository implements UserProfileRepositoryInterface
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
    ): int {
        return UserProfile::upsert(
            [
                [
                    'user_id'         => $userId,
                    'first_name'      => $firstName,
                    'last_name'       => $lastName,
                    'first_name_kana' => $firstNameKana,
                    'last_name_kana'  => $lastNameKana,
                    'phone'           => $phoneNumber,
                    'post_code'       => $postCode,
                    'prefecture'      => $prefecture,
                    'address1'        => $address1,
                    'address2'        => $address2,
                ],
            ],
            [
                'user_id',
            ],
        );
    }
}
