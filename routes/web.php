<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Halaman Login
Route::get('/', function () {
    if(Auth::check()) return redirect('/dashboard'); // klo udh login langsung ke dashboard
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'login']);



// memanggil controller agar logika admin aktif
Route::get('/dashboard', [AbsensiController::class, 'index'])->middleware('auth');

// Proses Absen masuk
Route::post('/absen', [AbsensiController::class, 'store'])->middleware('auth');

// Proses absen pulang
Route::post('/absen-pulang', [AbsensiController::class, 'update'])->middleware('auth');

// Proses Logout
Route::post('/logout', function () {Auth::logout();
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/'); })->middleware('auth');
