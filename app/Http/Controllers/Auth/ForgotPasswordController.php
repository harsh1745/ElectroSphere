<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon; // Date handling ke liye

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller (Custom Flow)
    |--------------------------------------------------------------------------
    |
    | Yeh controller Email aur DOB verification ko handle karta hai.
    |
    */

    // Constructor agar aapko middleware use karna ho
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
        return view('auth.passwords.email'); // Yeh aapke custom form (email.blade.php) ko load karega.
    }

    /**
     * Email aur Date of Birth ko verify karke custom reset page par redirect karega.
     * Use karega: POST /password-verify-dob
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyDobAndRedirect(Request $request)
    {
        // 1. Validation: Email aur DOB dono required hain
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'date_of_birth' => 'required|date',
        ], [
            // --- UPDATED MESSAGES TO ENGLISH ---
            'email.exists' => 'This email address is not registered.',
            'date_of_birth.required' => 'The Date of Birth field is required.',
        ]);

        // Clean and format the date for database comparison (assuming Y-m-d format in DB)
        $dob = Carbon::parse($request->date_of_birth)->format('Y-m-d');

        // 2. Database Check: User ko email aur DOB se find karein
        $user = User::where('email', $request->email)
            ->whereDate('date_of_birth', $dob)
            ->first();

        // 3. Result Handling
        if (!$user) {
            // Failure: Agar user nahi mila (email ya DOB mismatch)
            return back()->withInput($request->only('email'))
                ->withErrors(['date_of_birth' => 'Email and Date of Birth do not match our records.']);
        }

        // Success: Agar user mil gaya, toh custom reset form par redirect karein
        // Hum user ki email ko URL mein bhej rahe hain.
        return redirect()->route('password.reset_custom', [
            'email' => $user->email
        ]);
    }
}
