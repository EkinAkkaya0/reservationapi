<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;  // Validator sınıfını import edin
use Illuminate\Support\Facades\Hash;  // Hash sınıfını import edin



class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|max:55',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    // JWT Token oluştur
    $token = JWTAuth::fromUser($user);

    return response()->json([
        'message' => 'User registered successfully',
        'token' => $token,
        'user' => $user,
    ], 201);
}

public function refresh()
{
    try {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        return response()->json([
            'token' => $newToken
        ]);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json(['error' => 'Token is invalid'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'Token not provided'], 400);
    }
}


public function logout(Request $request)
{
    try {
        // Geçerli token'ı al ve geçersiz kıl
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json(['error' => 'Token is invalid'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'Token not provided'], 400);
    }
}


    public function user(Request $request)
    {
        return response()->json(Auth::user());
    }
}
