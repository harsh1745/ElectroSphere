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
        if ($data['captcha_answer'] != Session::get('captcha_a')) {
            throw ValidationException::withMessages([
                'captcha_answer' => ['The verification answer is incorrect.'],
            ]);
        }
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'date_of_birth' => $data['date_of_birth'],
        ]);
    }
    protected function registered(Request $request, $user): ?RedirectResponse
    {
        if (is_null($user->email_verified_at)) {
            $user->forceFill(['email_verified_at' => Carbon::now()])->save();
        }

        Auth::logout();
        return redirect('/login')->with('success', 'Registration successful! You are now verified.');
    }
    public function showRegistrationForm()
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $answer = $num1 + $num2;

        Session::put('captcha_q', "What is $num1 + $num2?");
        Session::put('captcha_a', $answer);

        return view('auth.register');
    }
}
