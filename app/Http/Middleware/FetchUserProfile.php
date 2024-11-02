<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FetchUserProfile
{
    public function handle($request, Closure $next)
    {
        if (Session::has('api_token')) {
            $response = Http::withToken(Session::get('api_token'))->get('http://localhost:3000/api/auth/me');
            if ($response->successful()) {
                $user = $response->json();

                // Simpan seluruh data user ke dalam session
                Session::put('user', $user);
                // Simpan user ID secara spesifik di session
                Session::put('user_id', $user['id']); // Pastikan 'id' adalah field yang benar dari API
            } else {
                // Jika gagal mendapatkan user data, redirect ke halaman login atau tampilkan pesan error
                return redirect()->route('login')->withErrors('Gagal mendapatkan data user.');
            }
        }
        return $next($request);
    }
}
