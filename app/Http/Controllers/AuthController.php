<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleAbilityService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $roleAbilityService;

    public function __construct(RoleAbilityService $roleAbilityService)
    {
        $this->roleAbilityService = $roleAbilityService;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user()->load('rol');
        $abilities = $this->roleAbilityService->getAbilitiesForRole($user->rol->nombre);
        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
