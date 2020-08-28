<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('verificar.token', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $credentials = [
            "email" => $request->email,
            "password" => $request->password
        ];

        if ($token = auth()->attempt($credentials)) {
            $usuario = auth()->user();

            if (! is_null($usuario))
            {
                return $this->respondWithToken($token);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->invalidate();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $token = $this->respondWithToken(auth()->refresh());
        auth()->invalidate();
        return $token;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
