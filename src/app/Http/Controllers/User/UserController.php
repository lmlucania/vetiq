<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\UserService;
use App\Http\Controllers\Controller;
use App\Transformers\UserProfileTransformer;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    /**
     * @lrd:start
     * 個人情報を取得
     * @lrd:end
     */
    public function me()
    {
        $userProfile = $this->userService->getAuthUser();
        
        return fractal($userProfile, new UserProfileTransformer())->respond();
    }
}
