<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactAdminController extends Controller
{
    public function index()
    {
        $messages = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('messages'));
    }
}
