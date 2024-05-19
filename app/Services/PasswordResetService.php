<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function sendResetLink($email)
    {
        $status = Password::broker('usuarios')->sendResetLink($email);

        return $status;
    }

    public function resetPassword($token, $password, $email)
    {
        $tokenData = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$tokenData || !Hash::check($token, $tokenData->token)) {
            return false;
        }

        $resetData = [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
            'token' => $token,
        ];

        $status = Password::broker('usuarios')->reset($resetData, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        return $status === Password::PASSWORD_RESET;
    }
}
