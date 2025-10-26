<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\User; // User Model ko import karo
use Illuminate\Support\Carbon; // Date and Time handling ke liye

class UserController extends Controller
{
    /**
     * Users ki list dikhao (Index Page)
     */
    public function index()
    {
        // Sabhi users ko fetch karo, latest created user pehle dikhe
        $users = User::latest()->get();

        // resources/views/admin/users/index.blade.php view load karo
        return view('admin.users.index', compact('users'));
    }

    /**
     * Ek specific user ki details do (AJAX Modal ke liye)
     * @param \App\Models\User $user (Route Model Binding)
     */
    public function show(User $user)
    {
        // Yahaan hum sirf user object ko JSON format mein return kar denge
        // jisse JavaScript easily Modal mein data fill kar sake.
        return response()->json($user);
    }
    public function verify(User $user)
    {
        // Check karo agar user already verified nahi hai
        if (is_null($user->email_verified_at)) {

            // email_verified_at ko current timestamp se fill karo
            $user->update([
                'email_verified_at' => Carbon::now(), // Ya use now()
            ]);

            return redirect()->route('admin.users.index')->with('success', $user->name . ' has been successfully verified.');
        }

        return redirect()->route('admin.users.index')->with('warning', $user->name . ' is already verified.');
    }
}
