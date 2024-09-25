<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request){
        if ($request->isMethod('get')) {
            return view('auth.login');
        }
        // only method extracts the necessary fields from the request
        $credentials = $request->only('email', 'password');
        // Auth method tries to log in the user with the provided credentials
        if (Auth::attempt($credentials)) {
            // Session Security
            $request->session()->regenerate();

            return redirect('/blogs')->with('success', 'Welcome back!');
        }
        //method tries to log in the user with the provided credentials
        return back()->withErrors([
            'email' => 'Email do not match our records.',
            'password' => 'Password Incorrect'
        ]);


    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
