<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function index()
    {
        $user = Session::get('user');
        return view('profiles.index', compact('user'));
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
