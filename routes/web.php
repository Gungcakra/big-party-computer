<?php

use App\Livewire\Admin\AntarianServis;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Laporan;
use App\Livewire\Admin\Pengguna;
use App\Livewire\Admin\PenerimaanPerangkat;
use App\Livewire\Admin\Transaksi;
use App\Livewire\Auth\Login;
use App\Livewire\Pelanggan\CekStatus;
use App\Livewire\Teknisi\AntarianServis as TeknisiAntarianServis;
use App\Livewire\Teknisi\Dashboard as TeknisiDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman publik
Route::get('/', function () {
    return view('welcome');
});

Route::get('/cek-status', CekStatus::class)->name('cek-status');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',           Dashboard::class)          ->name('dashboard');
    Route::get('/penerimaan-perangkat', PenerimaanPerangkat::class)->name('penerimaan-perangkat');
    Route::get('/antrian-servis',      AntarianServis::class)     ->name('antrian-servis');
    Route::get('/transaksi',           Transaksi::class)          ->name('transaksi');
    Route::get('/laporan',             Laporan::class)            ->name('laporan');
    Route::get('/pengguna',            Pengguna::class)           ->name('pengguna');
});

// Teknisi
Route::middleware(['auth', 'role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard',      TeknisiDashboard::class)      ->name('dashboard');
    Route::get('/antrian-servis', TeknisiAntarianServis::class) ->name('antrian-servis');
});
