<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect(route('admin.dashboard'));
        }
        return view('admin.login');
    }
    protected function guard()
    {
        return Auth::guard('admin');
    }
    public function redirectTo()
    {
        if (Auth::guard('admin')->check()) {
            return route('admin.dashboard');
        }
        return '/admin/login';
    }

    // Login Method
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::guard('admin')->attempt($credentials, false)) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
