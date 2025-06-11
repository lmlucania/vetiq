<?php

declare(strict_types=1);

namespace App\Application\Service\User\UserProfile;

use App\Application\Service\Auth\AuthActorService;
use App\Domains\User\Repository\UserProfileRepositoryInterface;
use App\Domains\User\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UpdateMyProfileService
{
    public function __construct(
        private AuthActorService $authActorService,
        private UserRepositoryInterface $userRepository,
        private UserProfileRepositoryInterface $userProfileRepository,
    ) {
    }

    public function execute(
        string $email,
        string $firstName,
        string $lastName,
        ?string $firstNameKana,
        ?string $lastNameKana,
        string $phoneNumber,
        ?string $postCode,
        ?int $prefecture,
        ?string $address1,
        ?string $address2
    ): bool {
        $userId = $this->authActorService->getUserId();

        return DB::transaction(function () use (
            $userId,
            $email,
            $firstName,
            $lastName,
            $firstNameKana,
            $lastNameKana,
            $phoneNumber,
            $postCode,
            $prefecture,
            $address1,
            $address2
        ) {
            $updated = $this->userRepository->updateEmail($userId, $email);

            $upserted = $this->userProfileRepository->upsert(
                userId: $userId,
                firstName: $firstName,
                lastName: $lastName,
                firstNameKana: $firstNameKana,
                lastNameKana: $lastNameKana,
                phoneNumber: $phoneNumber,
                postCode: $postCode,
                prefecture: $prefecture,
                address1: $address1,
                address2: $address2,
            );

            // 両方成功した場合は、trueを返す
            return ($updated === true && $upserted >= 1);
        });
    }
}
