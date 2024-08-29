<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoatController;
use App\Http\Controllers\CareController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('/', function () {
    return view('auth.login');
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Routes for Goat Management
Route::middleware(['authcheck'])->group(function () {
    // Goat Routes
    Route::get('goats', [GoatController::class, 'index'])->name('goats.index');
    Route::get('goats/create', [GoatController::class, 'create'])->name('goats.create');
    Route::post('goats', [GoatController::class, 'store'])->name('goats.store');
    Route::get('goats/{id}', [GoatController::class, 'show'])->name('goats.show');
    Route::get('goats/{id}/edit', [GoatController::class, 'edit'])->name('goats.edit');
    Route::put('goats/{id}', [GoatController::class, 'update'])->name('goats.update');
    Route::delete('goats/{id}', [GoatController::class, 'destroy'])->name('goats.destroy');
    Route::get('/laporan', [GoatController::class, 'laporan'])->name('goats.laporan');
    
    // QR Code Routes
    Route::get('/generate-qr-code/{id}', [GoatController::class, 'generateQrCode'])->name('generate.qr.code');
    Route::get('/view-qr-code/{id}', [GoatController::class, 'viewQrCode'])->name('view.qr.code');

 


    Route::get('/goats/search', [GoatController::class, 'search'])->name('goats.search');
    Route::post('goats/scan', [GoatController::class, 'scanQRCode'])->name('goats.scan');
    

    Route::get('/listKambing', [GoatController::class, 'listKambing'])->name('goats.listKambing');


    // Care Routes
    Route::get('goats/{goatId}/cares', [CareController::class, 'index'])->name('goats.cares.index');
    Route::get('goats/{goatId}/cares/create', [CareController::class, 'create'])->name('goats.cares.create');
    Route::post('goats/{goatId}/cares', [CareController::class, 'store'])->name('goats.cares.store');
    Route::get('goats/{goatId}/cares/{careId}', [CareController::class, 'show'])->name('goats.cares.show');
    Route::get('goats/{goatId}/cares/{careId}/edit', [CareController::class, 'edit'])->name('goats.cares.edit');
    Route::put('goats/{goatId}/cares/{careId}', [CareController::class, 'update'])->name('goats.cares.update');
    Route::delete('goats/{goatId}/cares/{careId}', [CareController::class, 'destroy'])->name('goats.cares.destroy');

    // QR Code Scanning Route
    Route::get('/scan', [GoatController::class, 'scan'])->name('goats.scan');


    Route::get('/weight-history/{goatId}', function ($goatId) {
        return view('weight-history', ['goatId' => $goatId]);
    });
});
