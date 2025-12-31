<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\Admin\DestinasiController as AdminDestinasiController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// AUTH routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DESTINASI - routes (specific routes first)
Route::get('/destinasi', [DestinasiController::class, 'index'])->name('destinasi.index');
Route::get('/destinasi/search', [DestinasiController::class, 'search'])->name('destinasi.search');
Route::get('/destinasi/kategori/{kategori}', [DestinasiController::class, 'kategori'])->name('destinasi.kategori');

Route::get('/destinasi/{id}/tiktok', [DestinasiController::class, 'tiktok'])->name('destinasi.tiktok');
Route::get('/destinasi/{id}', [DestinasiController::class, 'show'])->name('destinasi.show');
// END DESTINASI

// ADMIN - Destinasi CRUD (Protected by auth middleware)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::post('destinasi/csv-preview', [AdminDestinasiController::class, 'csvPreview'])->name('destinasi.csvPreview');
    Route::resource('destinasi', AdminDestinasiController::class);
});