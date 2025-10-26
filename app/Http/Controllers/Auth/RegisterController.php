<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException; // Error throw karne ke liye

//add this method
use Illuminate\Auth\Events\Registered; // Naya use statement add karo
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Register Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles the registration of new users as well as their
        | validation and creation. By default this controller uses a trait to
        | provide this functionality without requiring any additional code.
        |
        */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'date_of_birth' => ['required', 'date'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // 1. OFFLINE CAPTCHA VALIDATION
        if ($data['captcha_answer'] != Session::get('captcha_a')) {
            // Validation fail hone par error throw karo
            throw ValidationException::withMessages([
                'captcha_answer' => ['The verification answer is incorrect.'],
            ]);
        }



        // 2. USER CREATION with INSTANT VERIFICATION
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'date_of_birth' => $data['date_of_birth'],

            // ✅ CAPTCHA PASS HUA TOH INSTANTLY VERIFIED
            // 'email_verified_at' => Carbon::now(),
        ]);
    }
    // Add this custom code for redirect pages
    protected function registered(Request $request, $user): ?RedirectResponse
    {
        // 1. User object ko manually verify karo
        if (is_null($user->email_verified_at)) {
            $user->forceFill(['email_verified_at' => Carbon::now()])->save();
        }

        // 2. Logout and Redirect
        Auth::logout(); // User ko register hone ke baad logout karna
        return redirect('/login')->with('success', 'Registration successful! You are now verified.'); // register ke baad login page
    }
    public function showRegistrationForm()
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $answer = $num1 + $num2;

        // Question aur Answer ko Session mein store karo
        Session::put('captcha_q', "What is $num1 + $num2?");
        Session::put('captcha_a', $answer);

        return view('auth.register'); // Tumhari registration view file
    }
}
