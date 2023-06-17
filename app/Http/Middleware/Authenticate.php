<?php

namespace App\Http\Middleware;

use App\Models\UserActive;
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

        // if(!empty($roles) && in_array('verified', $roles)){
        //     $userActive = UserActive::find($user->id_user_active)->first();

        //     if($user->authorization_level == 1 && $userActive->phone_number == 0 && $userActive->email == 0 && $userActive->id_card == 0 && $userActive->tax_registration_number == 0 && $userActive->user_bank == 0){
        //         return response()->json(['error' => 'Unauthorized', 'message' => 'authorization not verified, please contact us!',], 401);
        //     }

        //     if($user->authorization_level == 2 && $userActive->phone_number == 0 && $userActive->email == 0 && $userActive->id_card == 0 && $userActive->tax_registration_number == 0 && $userActive->user_bank == 0 && $userActive->user_business == 0){
        //         return response()->json(['error' => 'Unauthorized', 'message' => 'authorization not verified, please contact us!',], 401);
        //     }
        // }


        return $next($request);
    }
}
