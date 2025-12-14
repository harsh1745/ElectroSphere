<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * ============================
     * MY ACCOUNT (VIEW PROFILE)
     * ============================
     */
    public function index()
    {
        $user = Auth::user();
        return view('frontend.account.user.index', compact('user'));
    }

    /**
     * ============================
     * EDIT PROFILE FORM
     * ============================
     */
    public function edit()
    {
        $user = Auth::user();
        return view('frontend.account.user.edit', compact('user'));
    }

    /**
     * ============================
     * UPDATE PROFILE
     * ============================
     */

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validate basic profile fields
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        // Update profile fields
        $user->name = $request->name;
        $user->date_of_birth = $request->date_of_birth;

        // If user wants to change password
        if ($request->filled('current_password') || $request->filled('password')) {

            // Validate current + new passwords
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            // Check if old password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Your current password is incorrect.',
                ]);
            }

            // Save new password
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('account')->with('success', 'Profile updated successfully!');
    }



    /**
     * ============================
     * USER INVOICES LIST
     * ============================
     */
    public function invoicesIndex()
    {
        $userId = Auth::id();

        $invoices = Order::where('user_id', $userId)
            ->latest()
            ->get();

        return view('frontend.account.invoices.index', compact('invoices'));
    }

    /**
     * ============================
     * SHOW SINGLE INVOICE
     * ============================
     */
    public function showInvoice($order_id)
    {
        $order = Order::where('id', $order_id)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('frontend.invoice.show', compact('order'));
    }
}
