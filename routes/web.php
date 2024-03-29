<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\API\VclaimBPJSController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TarifKelompokLayananController;
use App\Http\Controllers\TarifLayananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VclaimController;
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
Route::prefix('antrian')->name('antrian.')->group(function () {
    Route::get('console', [AntrianController::class, 'console'])->name('console');
    Route::get('checkin', [AntrianController::class, 'checkin'])->name('checkin');
    Route::get('checkin_update', [AntrianController::class, 'checkin_update'])->name('checkin_update');
});

Route::prefix('antrian')->name('antrian.')->middleware(['auth', 'verified'])->group(function () {
    // console
    Route::get('console_jadwaldokter/{poli}/{tanggal}', [AntrianController::class, 'console_jadwaldokter'])->name('console_jadwaldokter');
    Route::get('tambah_offline/{poli}/{dokter}/{jam}', [AntrianController::class, 'tambah_offline'])->name('tambah_offline');
    Route::get('laporan', [AntrianController::class, 'laporan'])->name('laporan');
    // pendafataran
    Route::get('pendaftaran', [AntrianController::class, 'pendaftaran'])->name('pendaftaran')->middleware('permission:pendaftaran');
    Route::get('panggil_pendaftaran/{kodebooking}', [AntrianController::class, 'panggil_pendaftaran'])->name('panggil_pendaftaran')->middleware('permission:pendaftaran');
    Route::post('update_offline', [AntrianController::class, 'update_offline'])->name('update_offline')->middleware('permission:pendaftaran');
    Route::post('update_pendaftaran_online', [AntrianController::class, 'update_pendaftaran_online'])->name('update_pendaftaran_online')->middleware('permission:pendaftaran');
    Route::get('batal_antrian/{kodebooking}', [AntrianController::class, 'batal_antrian'])->name('batal_antrian')->middleware('permission:pendaftaran');
    // pembayaran
    Route::get('pembayaran', [AntrianController::class, 'pembayaran'])->name('pembayaran')->middleware('permission:kasir');
    Route::post('update_pembayaran', [AntrianController::class, 'update_pembayaran'])->name('update_pembayaran')->middleware('permission:kasir');
    // poliklinik
    Route::get('poli', [AntrianController::class, 'poli'])->name('poli')->middleware('permission:poliklinik');
    Route::get('panggil_poli/{kodebooking}', [AntrianController::class, 'panggil_poli'])->name('panggil_poli')->middleware('permission:poliklinik');
    Route::get('{kodebookig}/edit', [AntrianController::class, 'edit'])->name('edit')->middleware('permission:poliklinik');
    Route::get('lanjut_farmasi/{kodebooking}', [AntrianController::class, 'lanjut_farmasi'])->name('lanjut_farmasi')->middleware('permission:poliklinik');
    Route::get('selesai/{kodebooking}', [AntrianController::class, 'selesai'])->name('selesai')->middleware('permission:poliklinik');
    // farmasi
    Route::get('farmasi', [AntrianController::class, 'farmasi'])->name('farmasi')->middleware('permission:farmasi');
    Route::get('panggil_farmasi/{kodebooking}', [AntrianController::class, 'panggil_farmasi'])->name('panggil_farmasi')->middleware('permission:farmasi');
    Route::get('racik_farmasi/{kodebooking}', [AntrianController::class, 'racik_farmasi'])->name('racik_farmasi')->middleware('permission:farmasi');
    Route::get('selesai_farmasi/{kodebooking}', [AntrianController::class, 'selesai_farmasi'])->name('selesai_farmasi')->middleware('permission:farmasi');

    Route::get('{kodebookig}/show', [AntrianController::class, 'show'])->name('show');

    Route::get('display_pendaftaran', [AntrianController::class, 'display_pendaftaran'])->name('display_pendaftaran');
    Route::get('/', [AntrianController::class, 'index'])->name('index');
    Route::get('cari_pasien/{nik}', [AntrianController::class, 'cari_pasien'])->name('cari_pasien');

    Route::prefix('ref')->name('ref.')->group(function () {
        Route::get('poli', [AntrianController::class, 'ref_poli'])->name('poli');
        Route::get('get_poli_bpjs', [AntrianController::class, 'get_poli_bpjs'])->name('get_poli_bpjs');
        Route::get('dokter', [AntrianController::class, 'ref_dokter'])->name('dokter');
        Route::get('get_dokter_bpjs', [AntrianController::class, 'get_dokter_bpjs'])->name('get_dokter_bpjs');
        Route::get('jadwaldokter', [AntrianController::class, 'ref_jadwaldokter'])->name('jadwaldokter');
        Route::get('get_jadwal_bpjs', [AntrianController::class, 'get_jadwal_bpjs'])->name('get_jadwal_bpjs');
    });

    Route::post('store', [AntrianController::class, 'store'])->name('store');
    // Route::get('offline', [AntrianController::class, 'offline'])->name('offline');
    // Route::get('offline/add/{poli}', [AntrianController::class, 'offline_add'])->name('offline_add');
    // pendaftaran
    Route::get('baru_online/{kodebooking}', [AntrianController::class, 'baru_online'])->name('baru_online');
    Route::post('simpan_baru_online/{kodebooking}', [AntrianController::class, 'simpan_baru_online'])->name('simpan_baru_online');

    Route::get('baru_offline/{kodebooking}', [AntrianController::class, 'baru_offline'])->name('baru_offline');
});
// admin user role permission
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'permission:admin'])->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
});
// vcalim
Route::prefix('vclaim')->name('vclaim.')->middleware(['auth', 'verified', 'permission:bpjs'])->group(function () {
    Route::get('monitoring_pelayanan_peserta', [VclaimController::class, 'monitoring_pelayanan_peserta'])->name('monitoring_pelayanan_peserta');
    Route::delete('delete_sep/{noSep}', [VclaimController::class, 'delete_sep'])->name('delete_sep');
    Route::get('data_surat_kontrol', [VclaimController::class, 'data_surat_kontrol'])->name('data_surat_kontrol');
    Route::delete('delete_surat_kontrol/{noSurat}', [VclaimController::class, 'delete_surat_kontrol'])->name('delete_surat_kontrol');
});

Route::resource('kunjungan', KunjunganController::class);
Route::resource('poli', PoliklinikController::class);
Route::resource('jadwaldokter', JadwalDokterController::class);
Route::resource('dokter', DokterController::class);
Route::resource('tarif_kelompok_layanan', TarifKelompokLayananController::class);
Route::resource('tarif_layanan', TarifLayananController::class);
