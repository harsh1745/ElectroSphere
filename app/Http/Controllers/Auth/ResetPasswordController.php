<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{


    /**
     * Show the custom password reset form.
     * @param string $email
     * @return \Illuminate\View\View
     */
    public function showResetFormCustom($email)
    {
        return view('auth.passwords.reset')->with(
            ['email' => $email, 'token' => 'NO_TOKEN_REQUIRED']
        );
    }

    /**
     * Handle the custom password reset request.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPasswordCustom(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'User not found.']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('login')->with('status', 'Success! Your password has been updated. Please log in now.');
    }
}
