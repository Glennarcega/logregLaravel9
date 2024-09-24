<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show Registration Form
    public function showRegistrationForm() {
        return view('auth.register');
    }

    // Handle Registration
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the user in
        Auth::attempt($request->only('email', 'password'));

        // Redirect to the dashboard or home
        return redirect()->route('home');
    }

    // Show Login Form
    public function showLoginForm() {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home');
        }

        // Return back with an error if login fails
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Handle Logout
    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
