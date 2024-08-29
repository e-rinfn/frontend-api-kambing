<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CareController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL') . '/cares';
    }

    public function index($goatId)
{
    // Fetch specific goat details
    $response = Http::withToken(Session::get('api_token'))
        ->get(env('API_URL') . '/goats/' . $goatId);
    $goat = $response->json();
    
    if ($response->failed()) {
        abort(404, 'Goat not found');
    }
    
    // Fetch care events for the specific goat
    $response = Http::withToken(Session::get('api_token'))
        ->get(env('API_URL') . '/cares', [
            'GoatId' => $goatId // Pass the goatId as a query parameter
        ]);
    $careEvents = $response->json();
    
    if ($response->failed()) {
        abort(404, 'Care events not found');
    }
    
    // Ensure that care events are filtered by the goatId
    $careEvents = array_filter($careEvents, function($care) use ($goatId) {
        return isset($care['GoatId']) && $care['GoatId'] == $goatId;
    });

    // Return the view with goat details and care events
    return view('cares.index', compact('goat', 'careEvents', 'goatId'));
}

    


public function create($goatId)
{
    // Fetch specific goat details
    $response = Http::withToken(Session::get('api_token'))
        ->get(env('API_URL') . '/goats/' . $goatId);
    $goat = $response->json();

    if ($response->failed()) {
        abort(404, 'Goat not found');
    }

    return view('cares.create', compact('goat', 'goatId'));
}

public function show($goatId, $careId)
{
    // Fetch specific care event details
    $response = Http::withToken(Session::get('api_token'))
        ->get($this->apiUrl . '/' . $careId);
    $care = $response->json();

    if ($response->failed()) {
        abort(404, 'Care event not found');
    }

    // Fetch specific goat details
    $response = Http::withToken(Session::get('api_token'))
        ->get(env('API_URL') . '/goats/' . $goatId);
    $goat = $response->json();

    return view('cares.show', compact('care', 'goat', 'goatId'));
}

public function edit($goatId, $careId)
{
    // Fetch specific care event details
    $response = Http::withToken(Session::get('api_token'))
        ->get($this->apiUrl . '/' . $careId);
    $care = $response->json();

    if ($response->failed()) {
        abort(404, 'Care event not found');
    }

    // Fetch specific goat details
    $response = Http::withToken(Session::get('api_token'))
        ->get(env('API_URL') . '/goats/' . $goatId);
    $goat = $response->json();

    return view('cares.edit', compact('care', 'goat', 'goatId'));
}

public function store(Request $request, $goatId)
{
    // Validasi data yang diterima
    $validatedData = $request->validate([
        'tanggal' => 'required|date',
        'tipePerawatan' => 'required|string|max:255',
        'catatan' => 'nullable|string|max:1000',
        // tambahkan aturan validasi lainnya sesuai kebutuhan
    ]);

    // Tambahkan goat_id ke dalam data yang telah divalidasi
    $validatedData['GoatId'] = $goatId;

    // Kirim data perawatan ke API
    $response = Http::withToken(Session::get('api_token'))
        ->post($this->apiUrl, $validatedData);

    if ($response->failed()) {
        return redirect()->back()->withErrors('Failed to create care event');
    }

    return redirect()->route('goats.cares.index', $goatId);
}

public function update(Request $request, $goatId, $careId)
{
    // Update care event data
    $response = Http::withToken(Session::get('api_token'))
        ->put($this->apiUrl . '/' . $careId, $request->all());

    if ($response->failed()) {
        return redirect()->back()->withErrors('Failed to update care event');
    }

    return redirect()->route('goats.cares.index', $goatId);
}

public function destroy($goatId, $careId)
{
    // Delete specific care event
    $response = Http::withToken(Session::get('api_token'))
        ->delete($this->apiUrl . '/' . $careId);

    if ($response->failed()) {
        return redirect()->back()->withErrors('Failed to delete care event');
    }

    return redirect()->route('goats.cares.index', $goatId);
}


}
