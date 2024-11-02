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
        // Jika login sukses, ambil token dan simpan dalam session
        $token = $response->json('token');
        Session::put('api_token', $token);
        return redirect('goats');  // Redirect ke halaman "goats"
    } else {
        // Ambil pesan error dari API jika ada
        $errorMessage = $response->json('message') ?? 'Usermane dan Password Tidak Cocok';
        
        // Kirim pesan error ke view
        return back()->withErrors(['message' => $errorMessage]);
    }
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
    // Custom error messages
    $messages = [
        'username.required' => 'Username tidak boleh kosong.',
        'username.max' => 'Username tidak lebih dari 255 karakter.',
        'password.required' => 'Password tidak boleh kosong.',
        'password.min' => 'Password memiliki minimal panjang 8 karakter.',
        'password.confirmed' => 'Password tidak sesuai.',
        'role.required' => 'Role is required.',
        'role.in' => 'Invalid role selected.',
    ];

    // Validate the request with custom messages
    $request->validate([
        'username' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|in:admin,pegawai',
    ], $messages);

    // Send a POST request to the API to register the user
    $response = Http::post(env('API_URL') . '/auth/register', [
        'username' => $request->input('username'),
        'password' => $request->input('password'),
        'role' => $request->input('role'),
    ]);

    if ($response->successful()) {
        // If registration is successful, redirect to the login page with a success message
        return redirect('login')->with('success', 'Account created successfully. Please login.');
    }

    // Check for specific error codes from the API and return custom error messages
    if ($response->status() === 422) {
        return back()->withErrors(['message' => 'The username is already taken. Please choose another one.']);
    }

    if ($response->status() === 400) {
        return back()->withErrors(['message' => 'Invalid data provided. Please check your inputs.']);
    }

    // If the API response indicates any other error, return a generic error message
    return back()->withErrors(['message' => 'Registration failed. Please try again later.']);
}


    public function getCurrentUser(Request $request)
    {
        // Ambil token dari header
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['error' => 'No token provided'], 401);
        }

        // Buat permintaan ke API Node.js
        $response = Http::withToken($token)->get('http://localhost:3000/api/auth/me');
        
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Unable to fetch user data'], $response->status());
        }
    }
}
