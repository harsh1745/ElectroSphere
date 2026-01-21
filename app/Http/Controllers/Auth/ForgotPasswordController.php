<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request the DOB verification (Fix for BadMethodCallException).
     * Use karega: GET /password/reset
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Use karega: POST /password-verify-dob
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyDobAndRedirect(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'date_of_birth' => 'required|date',
        ], [
            'email.exists' => 'This email address is not registered.',
            'date_of_birth.required' => 'The Date of Birth field is required.',
        ]);

        $dob = Carbon::parse($request->date_of_birth)->format('Y-m-d');

        $user = User::where('email', $request->email)
            ->whereDate('date_of_birth', $dob)
            ->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['date_of_birth' => 'Email and Date of Birth do not match our records.']);
        }
        return redirect()->route('password.reset_custom', [
            'email' => $user->email
        ]);
    }
}
