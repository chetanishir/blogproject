<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Method to handle both GET and POST requests for user registration
    public function register(Request $request)
    {
        // Check if the request method is GET
        if ($request->isMethod('get')) {
            // Return the registration view to the user
            return view('auth.register');
        }

        // Validate the incoming request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],        // Name must be provided, must be a string, and max length is 255 characters
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // Email must be provided, valid email format, max length is 255 characters, and unique in the users table
            'password' => ['required', 'string', 'min:5', 'confirmed'], // Password must be provided, must be a string, min length is 8 characters, and must be confirmed (i.e., matched with password_confirmation field)
        ]);

        // Create a new user with the validated data
        $user = User::create([
            'name' => $request->name,                           // Assign the name from the request to the user
            'email' => $request->email,                         // Assign the email from the request to the user
            'password' => Hash::make($request->password),       // Hash the password and assign it to the user
        ]);

        // Log the newly created user in
        Auth::login($user);

        // Redirect the user to the home route
        return redirect('/');
    }
}
