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
}
