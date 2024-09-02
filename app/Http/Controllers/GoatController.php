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
        return view('goats.listKambing', compact('goats'));
    }

    public function index()
{
    $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl);
    $goats = $response->json();

    $totalGoats = count($goats);
    $totalMale = count(array_filter($goats, fn($goat) => $goat['kelamin'] === 'Jantan'));
    $totalFemale = count(array_filter($goats, fn($goat) => $goat['kelamin'] === 'Betina'));

    $averageWeight = array_sum(array_column($goats, 'bobot')) / $totalGoats;
    $mostCommonBreed = array_count_values(array_column($goats, 'jenis'));
    arsort($mostCommonBreed);
    $mostCommonBreed = array_key_first($mostCommonBreed);

    // Additional metrics
    $cageDistribution = array_count_values(array_column($goats, 'posisiKandang'));
    $ageDistribution = $this->calculateAgeDistribution($goats);

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
                return redirect()   ->back()->withErrors('Data Kambing tidak ditemukan atau Nomor Tag tidak tersedia.');
            }
        
            return view('goats.show', compact('goat'));
        }
        

        public function update(Request $request, $id)
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
                'UserId' => 'required|integer', // Validasi ID pengguna
            ]);
        
             // Menyertakan ID pengguna ke data yang akan dikirim
            $validatedData['UserId'] = $request->input('user_id');

            $response = Http::withToken(Session::get('api_token'))->post($this->apiUrl, $validatedData);

            if ($response->successful()) {
                return redirect('listKambing')->with('success', 'Data Kambing Berhasil Ditambahkan.');
            } else {
                return redirect()->back()->withErrors('Gagal Menambahkan Data Kambing.')->withInput();
            }
        }
        

    public function destroy($id)
    {
        Http::withToken(Session::get('api_token'))->delete($this->apiUrl . '/' . $id);
        return redirect('goats');
    }



public function generateQRCode($id)
{
    // Check if the goat exists by making a request to the API
    $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl . '/' . $id);

    if ($response->failed()) {
        return response('Goat not found', 404);
    }

    // Get the custom domain from the .env file
    $customDomain = env('APP_URL', 'https://72aa-36-74-40-25.ngrok-free.app');
    $goatUrl = $customDomain . '/goats/' . $id;

    // Create the QR code with the goat URL
    $qrCode = new QrCode($goatUrl);
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // Return the QR code image as a PNG
    return response($result->getString(), 200)
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
    $response = Http::withToken(Session::get('api_token'))->get($this->apiUrl);
    $goats = $response->json();

    $totalGoats = count($goats);
    $totalMale = count(array_filter($goats, fn($goat) => $goat['kelamin'] === 'Jantan'));
    $totalFemale = count(array_filter($goats, fn($goat) => $goat['kelamin'] === 'Betina'));
    $averageWeight = array_sum(array_column($goats, 'bobot')) / $totalGoats;
    $positions = collect($goats)->pluck('posisiKandang')->unique()->sort()->values();    
    
    return view('goats.laporan', compact('goats', 'totalGoats', 'totalMale', 'totalFemale', 'averageWeight', 'positions'));}
}
