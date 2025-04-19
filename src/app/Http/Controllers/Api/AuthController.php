<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiExceptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login",
     *     tags={"Authentication"},
     *     description="Authenticates the user and returns the user's API token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authenticated"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="1|abc123...jwt_token...")
     *             ),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials"),
     *             @OA\Property(property="status", type="integer", example=401)
     *         )
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return ApiExceptions::error('Invalid credentials', 401); //401: Unauthorized
        }

        $user = User::firstWhere('email', $request->email);

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API token for '.$user->email,
                    [],
                    now()->addMonth()
                )->plainTextToken,
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout",
     *     tags={"Authentication"},
     *     description="Signs out the user and destroys the API token.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('');
    }
}
