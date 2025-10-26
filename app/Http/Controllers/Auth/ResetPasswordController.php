<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // User model use karna hai

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller (Custom Flow)
    |--------------------------------------------------------------------------
    |
    | Yeh controller new password set karne ka process handle karta hai.
    | Hum Laravel ke default token mechanism ko skip kar rahe hain.
    |
    */

    // Default ResetsPasswords trait ko hatane ki zarurat nahi hai, bas naye methods add karein.

    /**
     * Show the custom password reset form.
     * @param string $email
     * @return \Illuminate\View\View
     */
    public function showResetFormCustom($email)
    {
        // Laravel ka default reset view use karein, lekin $token ki jagah sirf $email pass karein
        return view('auth.passwords.reset')->with(
            ['email' => $email, 'token' => 'NO_TOKEN_REQUIRED']
            // 'token' ki value koi bhi static string de dein, taki view load ho jaaye
        );
    }

    /**
     * Handle the custom password reset request.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPasswordCustom(Request $request)
    {
        // 1. Validation: Email, new password, aur confirmation ko validate karein
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. User Find karein
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // User nahi mila toh error
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'User not found.']);
        }

        // 3. Password Update karein
        $user->password = Hash::make($request->password);
        $user->save();

        // 4. Success: User ko login page par redirect karein
        // --- UPDATED SUCCESS MESSAGE (Short, English, E-commerce style) ---
        return redirect()->route('login')->with('status', 'Success! Your password has been updated. Please log in now.');
    }
}
