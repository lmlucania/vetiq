<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\UserProfile\DeleteMyProfileService;
use App\Application\Service\User\UserProfile\GetMyProfileService;
use App\Application\Service\User\UserProfile\UpdateMyProfileService;
use App\Application\Service\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UseProfile\UpdateUserProfileRequest;
use App\Transformers\UserProfileTransformer;

class UserController extends Controller
{
    public function __construct(
        private GetMyProfileService $getMyProfileService,
        private UpdateMyProfileService $updateMyProfileService,
        private DeleteMyProfileService $deleteMyProfileService,
    ) {
    }

    /**
     * @lrd:start
     * 個人情報を取得
     * @lrd:end
     */
    public function me()
    {
        $userProfile = $this->getMyProfileService->execute();

        return fractal($userProfile, new UserProfileTransformer())->respond();
    }

    /**
     * @lrd:start
     * 個人情報を更新
     * @lrd:end
     */
    public function update(UpdateUserProfileRequest $request)
    {
        $success = $this->updateMyProfileService->execute(
            email: $request->getEmail(),
            firstName: $request->getFirstName(),
            lastName: $request->getLastName(),
            firstNameKana: $request->getFirstNameKana(),
            lastNameKana: $request->getLastNameKana(),
            phoneNumber: $request->getPhoneNumber(),
            postCode: $request->getPostCode(),
            prefecture: $request->getPrefecture(),
            address1: $request->getAddress1(),
            address2: $request->getAddress2(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * 退会
     * @lrd:end
     */
    public function destroy()
    {
        $success = $this->deleteMyProfileService->execute();

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
