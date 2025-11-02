<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Admin login ke baad kahan redirect karna hai
    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        // Fix: Customer session ko redirect hone se rokta hai
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application's admin login form.
     */
    public function showLoginForm()
    {
        // 💥 FINAL FIX: Agar user 'web' (customer) guard se logged in hai, 
        // toh bhi hum yahan koi redirect nahi karenge.
        if (Auth::guard('admin')->check()) {
            // Agar Admin guard se hi logged in hai toh dashboard par bhej do
            return redirect(route('admin.dashboard'));
        }

        // Agar Admin logged in nahi hai (chahe Customer logged in ho ya na ho), 
        // toh Login form dikhao.
        return view('admin.login');
    }

    // Yeh method override kiya gaya hai taki Admin guard use ho
    protected function guard()
    {
        return Auth::guard('admin');
    }

    // Custom redirectTo logic to handle redirects after successful login (optional but good practice)
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

        // ✅ CHANGE: 'remember' feature ko forcefully 'false' set kar diya gaya hai.
        // Yeh ensure karta hai ki browser close hone par session expire ho (kyunki 'expire_on_close' true hoga).
        // Isse 60 minute timeout bhi apply hoga.
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
