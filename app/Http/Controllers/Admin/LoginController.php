<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Login Controller
    |--------------------------------------------------------------------------
    | Handles Admin authentication using the 'admin' guard.
    */

    public function __construct()
    {
        // Guests only can access the login form
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // 1. Validation
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Attempt Login using the 'admin' guard
        // **IMPORTANT:** Yahaan 'admin' guard use ho raha hai
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {

            // Re-generate session to prevent session fixation
            $request->session()->regenerate();

            // Redirect to Admin Dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // 3. Login Failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log out the user from the 'admin' guard
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
