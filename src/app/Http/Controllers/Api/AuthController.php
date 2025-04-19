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
     * Login
     *
     * Authenticates the user and returns the user's API token.
     *
     * @unauthenticated
     *
     * @group Authentication
     *
     * @response 200 {
            "message": "Authenticated",
            "data": {
                "token": "{YOUR_AUTH_KEY}"
            },
            "status": 200
        }
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
     * Logout
     *
     * Signs out the user and destroy's the API token.
     *
     * @group Authentication
     *
     * @response 200 {}
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('');
    }
}
