<?php

use App\Http\Controllers\AntrianController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// antrian routes
Route::prefix('antrian')->name('antrian.')->middleware(['auth', 'verified'])->group(function () {
    Route::prefix('ref')->name('ref.')->group(function () {
        Route::get('poli', [AntrianController::class, 'ref_poli'])->name('poli');
        // Route::get('get_poli_bpjs', [AntrianController::class, 'get_poli_bpjs'])->name('get_poli_bpjs');
        // Route::get('dokter', [AntrianController::class, 'ref_dokter'])->name('dokter');
        // Route::get('get_dokter_bpjs', [AntrianController::class, 'get_dokter_bpjs'])->name('get_dokter_bpjs');
        // Route::get('jadwaldokter', [AntrianController::class, 'ref_jadwaldokter'])->name('jadwaldokter');
        // Route::get('get_jadwal_bpjs', [AntrianController::class, 'get_jadwal_bpjs'])->name('get_jadwal_bpjs');
    });
    Route::get('tambah', [AntrianController::class, 'tambah'])->name('tambah');
    Route::post('store', [AntrianController::class, 'store'])->name('store');
    Route::get('checkin', [AntrianController::class, 'checkin'])->name('checkin');
    Route::get('checkin_update', [AntrianController::class, 'checkin_update'])->name('checkin_update');

    Route::get('offline', [AntrianController::class, 'offline'])->name('offline');
    Route::get('offline/add/{poli}', [AntrianController::class, 'offline_add'])->name('offline_add');

    Route::get('pendaftaran', [AntrianController::class, 'pendaftaran'])->name('pendaftaran');
    Route::get('panggil/{kodebooking}', [AntrianController::class, 'panggil'])->name('panggil');
});