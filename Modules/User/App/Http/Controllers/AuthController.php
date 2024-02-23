<?php

namespace Modules\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {
            $credentials = request(['email', 'password']);
            $doLogin = auth()->attempt($credentials);
            if($doLogin == false)
            {
                return response()->json(['error' => 'Unauthorized'], 401);
            }else {
                $user = JWTAuth::user();
                $token = JWTAuth::fromUser($user);
                $user->abilities = [
                    'manage'=>'all',
                ];
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successfully',
                    'token_type' => 'bearer',
                    'expires_in' => 6000, // Token expiration time in minutes
                    'user' => auth()->user()->name,
                    'user_id' => auth()->user()->id,
                    'user_email' => auth()->user()->email,
                    'user_role' => auth()->user()->role ?? "admin",
                    'user_status' => auth()->user()->status,
                    'user_created_at' => auth()->user()->created_at,
                    'user_updated_at' => auth()->user()->updated_at,
                    'access_token' => $token,
                    'abilities' => $user->abilities,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 6000
        ]);
    }
}
