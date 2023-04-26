<?php

namespace App\Http\Middleware;
use Closure;
use Exception;
use JWTAuth;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
// use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         return route('login');
    //     }
    // }

    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Session Expired!',], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Session Expired!',], 401);
        }
        if (!empty($roles) && !in_array($user->authorization_level, $roles)) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'authorization level not allowed',], 401);
        }

        return $next($request);
    }
}
