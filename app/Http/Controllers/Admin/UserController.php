<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\User; 
use Illuminate\Support\Carbon;

class UserController extends Controller
{

    public function index()
    {
        $users = User::latest()->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Ek specific user ki details do (AJAX Modal ke liye)
     * @param \App\Models\User $user (Route Model Binding)
     */
    public function show(User $user)
    {
        return response()->json($user);
    }
    public function verify(User $user)
    {
        if (is_null($user->email_verified_at)) {

            $user->update([
                'email_verified_at' => Carbon::now(), // Ya use now()
            ]);

            return redirect()->route('admin.users.index')->with('success', $user->name . ' has been successfully verified.');
        }

        return redirect()->route('admin.users.index')->with('warning', $user->name . ' is already verified.');
    }
}
