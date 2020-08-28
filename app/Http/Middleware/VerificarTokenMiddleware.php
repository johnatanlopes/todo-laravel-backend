<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerificarTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            // do whatever you want to do if a token is expired
            return response()->json(['error' => "token_expired"], 401);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            // do whatever you want to do if a token is invalid
            return response()->json(['error' => "token_invalid"], 401);

        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {

            // do whatever you want to do if a token is not present
            return response()->json(['error' => "token_blacklisted"], 401);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            // do whatever you want to do if a token is not present
            return response()->json(['error' => "token_not_present"], 401);
        }

        return $next($request);
    }
}
