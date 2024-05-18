<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleAbilityService;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $roleAbilityService;
    protected $passwordResetService;

    public function __construct(RoleAbilityService $roleAbilityService, PasswordResetService $passwordResetService)
    {
        $this->roleAbilityService = $roleAbilityService;
        $this->passwordResetService = $passwordResetService;
    }

    public function login(Request $request)
    {
        $credentials = $this->validateLogin($request);

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

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $email = $this->validateEmail($request);

            $status = $this->passwordResetService->sendResetLink($email);

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'Reset link sent to your email.'])
                : response()->json(['message' => 'Unable to send reset link.'], 500);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            Log::error('Password reset link error: ' . $e->getMessage());
            return $this->serverErrorResponse();
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $data = $this->validateResetPassword($request);

            $success = $this->passwordResetService->resetPassword($data['token'], $data['password'], $data['email']);

            return $success
                ? response()->json(['message' => 'Password has been reset.'])
                : response()->json(['message' => 'Invalid token or email.'], 400);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return $this->serverErrorResponse();
        }
    }

    protected function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }

    protected function validateEmail(Request $request)
    {
        return $request->validate(['email' => 'required|email']);
    }

    protected function validateResetPassword(Request $request)
    {
        return $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|string|email',
        ]);
    }

    protected function validationErrorResponse(ValidationException $e)
    {
        return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
    }

    protected function serverErrorResponse()
    {
        return response()->json(['message' => 'An error occurred. Please try again later.'], 500);
    }
}
