<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinasiController;

Route::get('/', function () {
    return view('welcome');
});

// DESTINASI - routes (specific routes first)
Route::get('/destinasi', [DestinasiController::class, 'index'])->name('destinasi.index');
Route::get('/destinasi/search', [DestinasiController::class, 'search'])->name('destinasi.search');
Route::get('/destinasi/kategori/{kategori}', [DestinasiController::class, 'kategori'])->name('destinasi.kategori');

Route::get('/destinasi/{id}/tiktok', [DestinasiController::class, 'tiktok'])->name('destinasi.tiktok');
Route::get('/destinasi/{id}', [DestinasiController::class, 'show'])->name('destinasi.show');
// END DESTINASI