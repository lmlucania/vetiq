<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Exceptions\UnauthorizedException;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    /**
     * @lrd:start
     * ログイン
     * @lrd:end
     */
    public function login(LoginRequest $request)
    {
        if (Auth::guard('users')->attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();
            return response()->json(['message' => 'ログインが成功しました。']);
        }

        throw new UnauthorizedException('ログインに失敗しました。メールアドレスまたはパスワードが正しくありません。');
    }

    /**
     * @lrd:start
     * ログアウト
     * @lrd:end
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'ログアウトが成功しました。']);
    }
}
