<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        if (Address::where('user_id', Auth::id())->count() >= 2) {
            return back()->with('error', 'You can only save a maximum of 2 addresses.');
        }

        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'street_address' => $request->street_address,
            'apartment_suite' => $request->apartment_suite,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
        ]);

        $address->update($request->all());

        return back()->with('success', 'Address updated successfully!');
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return back()->with('success', 'Address deleted!');
    }
}
