<?php

use App\Http\Controllers\GuruController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriKonselingController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\PermohonanKonselingController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAkademikController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {

    // Users
    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('orangtua', OrangtuaController::class);

    // Kelas & Tahun Akademik
    Route::resource('kelas', KelasController::class);
    Route::resource('tahun-akademik', TahunAkademikController::class);

    // Kategori Konseling
    Route::resource('kategori-konseling', KategoriKonselingController::class);

    // Permohonan Konseling
    Route::get('permohonan-konseling/prioritas', [PermohonanKonselingController::class, 'prioritas'])->name('permohonan.prioritas');
    Route::resource('permohonan-konseling', PermohonanKonselingController::class);
});
