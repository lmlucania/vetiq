<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\LoginRequest;
use App\Models\StaffModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/hospital/login",
     *     tags={"Hospital"},
     *     summary="ログイン",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Requests~1Hospital~1LoginRequest")
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="ログイン成功",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  type="string",
     *                  example="9|bWKC0YcBfDE1lOsBPmKAwMTTN91xu7iAEyxJcF1j1e936bed"
     *              ),
     *              @OA\Property(
     *                  property="expires_at",
     *                  type="string",
     *                  format="date-time",
     *                  example="2025-04-29T10:23:00Z"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="ログイン失敗",
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = StaffModel::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new UnauthorizedException('ログインに失敗しました。メールアドレスまたはパスワードが正しくありません。');
        }

        $token = $this->createToken($user);

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/hospital/logout",
     *     tags={"Hospital"},
     *     summary="ログアウト",
     *     @OA\Response(
     *          response="200",
     *          description="ログアウト成功",
     *     ),
     * )
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'ログアウトが成功しました。']);
    }

    /**
     * @OA\Post(
     *     path="/hospital/refresh-token",
     *     tags={"Hospital"},
     *     summary="トークンリフレッシュ",
     *     @OA\Response(
     *          response="200",
     *          description="ログアウト成功",
     *     ),
     * )
     */
    public function refreshToken(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        $token = $this->createToken($user);

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
        ]);
    }

    /**
     * トークンを作成
     * @param StaffModel $user
     * @return NewAccessToken
     */
    private function createToken(StaffModel $user): NewAccessToken
    {
        // 未設定の場合は有効期限無し
        $expiresAt = null;
        if (config('sanctum.expiration') != null) {
            $expiresAt = now()->addMinutes((int)config('sanctum.expiration'));
        }

        return $user->createToken(
            'PC', ['*'], $expiresAt,
        );
    }
}
