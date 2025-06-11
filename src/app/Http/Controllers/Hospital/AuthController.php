<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @lrd:start
     * ログイン
     * @lrd:end
     */
    public function login(LoginRequest $request)
    {
        if (Auth::guard('staff-members')->attempt($request->only(['email', 'password']))) {
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
