<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        // $expiredToken = $request->is_remember == true ? 7 * 24 * 60 : 1 * 60;
        $expiredToken = 10080;

        $token = Auth::setTTL($expiredToken)->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'server_time' => (int) round(microtime(true) * 1000),
            ], 401);
        }

        $user = Auth::user();
        // Add verified value to the user
        UserController::addVerifiedValueToTheData($user);
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ],
            'server_time' => (int) round(microtime(true) * 1000),
            ]);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
            'server_time' => (int) round(microtime(true) * 1000),
        ]);
    }

}
