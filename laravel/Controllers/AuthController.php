<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SigninRequest;

class AuthController extends Controller
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signin(SigninRequest $request)
    {
        if (! $token = auth()->claims([
            "iss" => null
        ])->attempt($request->validated())) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        if(auth()->user()->deleted_at){
            auth()->logout();
            return response()->json([
                "error" => "Akun anda telah terhapus",
            ],500);
        }

        return $this->respondWithToken($token);
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

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try{
            return $this->respondWithToken(auth()->claims(['iss' => Null])->refresh());
        }catch(\Exception $e){
            \Log::channel("coex")->info($e->getMessage());

            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException){
                $response['error'] = 'Token is blacklist';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $response['error'] = 'Token is expired but when refresh failed';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $response['error'] = 'Token is invalid';
            }else{
                $response['error'] = 'Token Not Found';
            }

            return response()->json($response,401);
        }
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
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
