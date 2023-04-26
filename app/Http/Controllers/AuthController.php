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
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'date_of_birth' => 'required|date',
            'full_name' => 'required|string',
            'gender' => 'required',
            'address' => 'required',
            'id_card' => 'required',
            'tax_registration_number' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'employment_status' => 'required',
            'authorization_level' => 'required',
            'id_user_active' => 'required',
            'id_user_bank' => 'required',
            'business_certificate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'address' => $request->address,
            'id_card' => $request->id_card,
            'tax_registration_number' => $request->tax_registration_number,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'employment_status' => $request->employment_status,
            'authorization_level' => $request->authorization_level,
            'id_user_active' => $request->id_user_active,
            'id_user_bank' => $request->id_user_bank,
            'business_certificate' => $request->business_certificate,
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}
