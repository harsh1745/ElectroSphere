<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotController extends Controller
{
    // SHOW EMAIL + DOB PAGE
    public function showForm()
    {
        return view('frontend.auth.custom-forgot');
    }

    // VERIFY USER
    public function checkUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'date_of_birth' => 'required|date',
        ]);

        $user = User::where('email', $request->email)
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        if (!$user) {
            return back()->with('error', 'Email or Date of Birth is Incorrect!');
        }

        return redirect()->route('custom.reset', $user->id);
    }


    // SHOW RESET PASSWORD FORM
    public function showResetForm($id)
    {
        $user = User::findOrFail($id);
        return view('frontend.auth.custom-reset', compact('user'));
    }

    // SAVE NEW PASSWORD AND RETURN TO PROFILE
    public function resetPassword(Request $request, $id)
    {
        if ($request->password !== $request->password_confirmation) {
            return back()->with('error', 'Passwords do not match!');
        }

        if (strlen($request->password) < 6) {
            return back()->with('error', 'Password must be at least 6 characters.');
        }

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account')->with('success', 'Password updated successfully!');
    }
}
