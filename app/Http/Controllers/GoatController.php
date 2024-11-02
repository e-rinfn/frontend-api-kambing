<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class GoatController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL') . '/goats';
    }

    public function listKambing()
{
    $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl);
    $goats = $response->json();

    // Filter kambing berdasarkan user_id dari pengguna yang sedang login
    $userId = Session::get('user.id');
    $filteredGoats = collect($goats)->where('UserId', $userId);

    return view('goats.listKambing', ['goats' => $filteredGoats]);
}


public function index()
{
    // Dapatkan ID pengguna yang sedang login
    $userId = Session::get('user.id');

    // Ambil data kambing dari API
    $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl);
    $goats = $response->json();

    // Filter data kambing berdasarkan user_id pengguna yang sedang login
    $filteredGoats = array_filter($goats, fn($goat) => $goat['UserId'] == $userId);

    // Hitung total kambing, jumlah kambing jantan dan betina, rata-rata bobot, dll.
    $totalGoats = count($filteredGoats);
    $totalMale = count(array_filter($filteredGoats, fn($goat) => $goat['kelamin'] === 'Jantan'));
    $totalFemale = count(array_filter($filteredGoats, fn($goat) => $goat['kelamin'] === 'Betina'));

    $averageWeight = $totalGoats > 0 ? array_sum(array_column($filteredGoats, 'bobot')) / $totalGoats : 0;
    $mostCommonBreed = array_count_values(array_column($filteredGoats, 'jenis'));
    arsort($mostCommonBreed);
    $mostCommonBreed = $mostCommonBreed ? array_key_first($mostCommonBreed) : 'N/A';

    // Additional metrics
    $cageDistribution = array_count_values(array_column($filteredGoats, 'posisiKandang'));
    $ageDistribution = $this->calculateAgeDistribution($filteredGoats);

    // Tampilkan view dengan data yang sudah difilter
    return view('goats.index', compact('totalGoats', 'totalMale', 'totalFemale', 'averageWeight', 'mostCommonBreed', 'cageDistribution', 'ageDistribution'));
}


private function calculateAgeDistribution($goats)
{
    $ageDistribution = [
        '0-1 tahun' => 0,
        '1-2 tahun' => 0,
        '2-3 tahun' => 0,
        '3+ tahun' => 0,
    ];

    foreach ($goats as $goat) {
        $age = now()->diffInYears($goat['tanggalLahir']);
        if ($age <= 1) {
            $ageDistribution['0-1 tahun']++;
        } elseif ($age <= 2) {
            $ageDistribution['1-2 tahun']++;
        } elseif ($age <= 3) {
            $ageDistribution['2-3 tahun']++;
        } else {
            $ageDistribution['3+ tahun']++;
        }
    }

    return $ageDistribution;
}
    

public function store(Request $request)
{
    // Validasi input dari permintaan
    $validatedData = $request->validate([
        'noTag' => 'required|string|max:255',
        'tanggalLahir' => 'required|date',
        'nama' => 'required|string|max:255',
        'bobot' => 'required|numeric',
        'kelamin' => 'required|in:Jantan,Betina',
        'jenis' => 'required|string|max:255',
        'induk' => 'required|string|max:255',
        'pejantan' => 'required|string|max:255',
        'posisiKandang' => 'required|string|max:255',
        'asal' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'status' => 'required|in:tersedia,terjual,mati',
    ]);

    // Tambahkan UserId ke data yang akan dikirim
    $validatedData['UserId'] = Session::get('user.id'); // Mengambil ID pengguna yang sedang login

    // Mengirim permintaan POST untuk menambahkan data kambing
    $response = Http::withToken(Session::get('api_token'))->post($this->apiUrl, $validatedData);

    // Memeriksa apakah response berhasil
    if ($response->successful()) {
        // Jika berhasil, arahkan ke halaman daftar kambing dengan pesan sukses
        return redirect('listKambing')->with('success', 'Data Kambing Berhasil Ditambahkan.');
    } else {
        // Jika gagal, arahkan kembali ke form tambah dengan pesan error
        return redirect()->back()->withErrors('Gagal Menambahkan Data Kambing.')->withInput();
    }
}




    
    // public function scanQRCode(Request $request)
    // {
    //     $qrCodeData = $request->input('qr_code_data');
    //     $searchTerm = $qrCodeData; // Assuming QR code contains the tag number
    
    //     return redirect()->route('goats.search', ['search' => $searchTerm]);
    // }
    




        public function show($id)
        {
            $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl . '/' . $id);
            $goat = $response->json();
        
            if (!$response->successful() || !isset($goat['noTag'])) {
                return redirect()->back()->withErrors('Data Kambing tidak ditemukan atau Nomor Tag tidak tersedia.');
            }
        
            return view('goats.show', compact('goat'));
        }
        

        public function update(Request $request, $id) {
            // Validasi input dari permintaan
            $validatedData = $request->validate([
                'noTag' => 'required|string|max:255',
                'tanggalLahir' => 'required|date',
                'nama' => 'required|string|max:255',
                'bobot' => 'required|numeric',
                'kelamin' => 'required|in:Jantan,Betina',
                'jenis' => 'required|string|max:255',
                'induk' => 'required|string|max:255',
                'pejantan' => 'required|string|max:255',
                'posisiKandang' => 'required|string|max:255',
                'asal' => 'required|string|max:255',
                'harga' => 'required|numeric',
                'status' => 'required|in:tersedia,terjual,mati',
            ]);

            // Tambahkan UserId ke data yang akan dikirim
            $validatedData['UserId'] = Session::get('user.id'); // Mengambil ID pengguna yang sedang login

            // Mengirim permintaan PUT untuk memperbarui data kambing berdasarkan ID
            $response = Http::withToken(Session::get('api_token'))
                            ->put($this->apiUrl . '/' . $id, $validatedData);

            // Memeriksa apakah respons berhasil
            if ($response->successful()) {
                // Jika berhasil, arahkan ke halaman daftar kambing dengan pesan sukses
                return redirect('listKambing')->with('success', 'Data Kambing Berhasil Diubah.');
            } else {
                // Jika gagal, arahkan kembali ke form edit dengan pesan error
                return redirect()->back()->withErrors('Gagal Mengubah Data Kambing.')->withInput();
            }
        }

        

    public function destroy($id)
    {
        Http::withToken(Session::get('api_token'))->delete($this->apiUrl . '/' . $id);
        return redirect('goats/listKambing')->with('success', 'Data Kambing Berhasil Dihapus.');
    }




    public function generateQRCode($id)
{
    // Fetch specific goat details
    $response = Http::withToken(Session::get('api_token'))
        ->get(env('API_URL') . '/goats/' . $id);

    if ($response->failed()) {
        return response('Goat not found', 404);
    }

    // Get the goat data
    $goat = $response->json();

    // Ensure the goat data has a tag number
    if (!isset($goat['noTag'])) {
        return response('Tag number not found for this goat', 404);
    }

    // Get the tag number
    $tagNumber = $goat['noTag'];

    // Create the URL for the goat details page
    $goatUrl = url('goats/' . $id);

    // Create the QR code URL using ZXing API, now encoding the full URL instead of just the tag number
    $zxingApiUrl = 'https://api.qrserver.com/v1/create-qr-code/';
    $qrCodeUrl = $zxingApiUrl . '?size=200x200&data=' . urlencode($goatUrl);

    // Fetch the QR code image from the ZXing API
    $qrCodeImage = file_get_contents($qrCodeUrl);

    if ($qrCodeImage === false) {
        return response('Failed to generate QR code', 500);
    }

    // Return the QR code image as a PNG
    return response($qrCodeImage, 200)
        ->header('Content-Type', 'image/png');
}





// Generate QR Code yang menggunakan noTag Kambing
// public function generateQRCode($id)
// {
//     // Fetch specific goat details
//     $response = Http::withToken(Session::get('api_token'))
//         ->get(env('API_URL') . '/goats/' . $id);

//     if ($response->failed()) {
//         return response('Goat not found', 404);
//     }

//     // Get the goat data
//     $goat = $response->json();

//     // Ensure the goat data has a tag number
//     if (!isset($goat['noTag'])) {
//         return response('Tag number not found for this goat', 404);
//     }

//     // Get the tag number
//     $tagNumber = $goat['noTag'];

//     // Create the QR code URL using ZXing API
//     $zxingApiUrl = 'https://api.qrserver.com/v1/create-qr-code/';
//     $qrCodeUrl = $zxingApiUrl . '?size=200x200&data=' . urlencode($tagNumber);

//     // Fetch the QR code image from the ZXing API
//     $qrCodeImage = file_get_contents($qrCodeUrl);

//     if ($qrCodeImage === false) {
//         return response('Failed to generate QR code', 500);
//     }

//     // Return the QR code image as a PNG
//     return response($qrCodeImage, 200)
//         ->header('Content-Type', 'image/png');
// }




    public function scan()
    {
        return view('goats.scan');
    }

    // Scan QR Code yang menggunakan link
    public function scanQRCode(Request $request)
    {
        $qrCodeData = $request->input('qr_code_data');
        $goat = json_decode($qrCodeData, true);
        return view('goats.show', compact('goat'));
    }

    public function create()
    {
     // Pastikan user ID tersedia di session
    $userId = session('user_id');

        return view('goats.create');
    }
    

    public function edit($id)
    {
        $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl . '/' . $id);
        $goat = $response->json();
        return view('goats.edit', compact('goat'));
    }

    public function weightHistory($id)
    {
        $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl . '/' . $id);
        $goat = $response->json();
        return view('goats.weightHistory', compact('goat'));
    }

    public function addWeight(Request $request, $id)
    {
        $validatedData = $request->validate([
            'bobot' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        $response = Http::withToken(Session::get('api_token'))->post($this->apiUrl . '/' . $id . '/weights', $validatedData);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Berat Kambing Berhasil Ditambahkan.');
        } else {
            return redirect()->back()->withErrors('Gagal Menambahkan Berat Kambing.')->withInput();
        }
    }

    public function laporan()
    {
       // Dapatkan ID pengguna yang sedang login
    $userId = Session::get('user.id');

    // Ambil data kambing dari API
    $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl);
    $goats = $response->json();

    // Filter data kambing berdasarkan user_id pengguna yang sedang login
    $filteredGoats = array_filter($goats, fn($goat) => $goat['UserId'] == $userId);

    // Hitung total kambing, jumlah kambing jantan dan betina, rata-rata bobot, dll.
    $totalGoats = count($filteredGoats);
    $totalMale = count(array_filter($filteredGoats, fn($goat) => $goat['kelamin'] === 'Jantan'));
    $totalFemale = count(array_filter($filteredGoats, fn($goat) => $goat['kelamin'] === 'Betina'));
    $averageWeight = $totalGoats > 0 ? array_sum(array_column($filteredGoats, 'bobot')) / $totalGoats : 0;

    // Dapatkan posisi kandang unik, urutkan, dan kembalikan sebagai koleksi
    $positions = collect($filteredGoats)->pluck('posisiKandang')->unique()->sort()->values();    

    // Tampilkan view dengan data yang sudah difilter
    return view('goats.laporan', compact('filteredGoats', 'totalGoats', 'totalMale', 'totalFemale', 'averageWeight', 'positions'));
}
}
