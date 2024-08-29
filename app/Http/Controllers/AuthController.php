<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $response = Http::post(env('API_URL') . '/auth/login', [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ]);

        if ($response->successful()) {
            $token = $response->json('token');
            Session::put('api_token', $token);
            return redirect('goats');
        }

        return back()->withErrors(['message' => 'Invalid credentials']);
    }

    public function logout()
    {
        Session::forget('api_token');
        return redirect('login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user',
        ]);

        // Send a POST request to the API to register the user
        $response = Http::post(env('API_URL') . '/auth/register', [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'role' => $request->input('role'),
        ]);

        if ($response->successful()) {
            // If registration is successful, redirect to the login page
            return redirect('login')->with('success', 'Account created successfully. Please login.');
        }

        // If the API response indicates an error, redirect back with an error message
        return back()->withErrors(['message' => 'Registration failed. Please try again.']);
    }
}
