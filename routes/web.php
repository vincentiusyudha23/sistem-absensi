<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['auth', 'role:admin'])->controller(AdminController::class)->prefix('admin')->name('admin.')->group(function(){
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/daftar-anggota', 'listUsers')->name('list_users');
    Route::get('/tambah-anggota', 'tambahAnggota')->name('tambah_anggota');
    Route::post('/tambah-anggota/store', 'storeAnggota')->name('tambah_anggota.store');
    Route::get('/edit-anggota/{id}', 'editAnggota')->name('edit_anggota');
    Route::post('/edit-anggota/update/{id}', 'updateAnggota')->name('edit_anggota.update');
    Route::get('/data-absensi', 'dataAbsensi')->name('data_absensi');
    Route::get('/fetch-data-absensi', 'getNewAbsensis')->name('fetch_data_absensi');
    Route::get('/detail-absensi/{id}', 'getDetailAbsen')->name('detail_absensi');
    Route::get('/detail-anggota/{id}', 'detailAnggota')->name('detail_anggota');
    Route::delete('/deleted-anggota/{id}', 'deletedUser')->name('deleted_anggota');
});

Route::middleware(['auth', 'role:user'])->controller(UserController::class)->name('user.')->group(function(){
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/store-absen', 'storeAbsen')->name('store_absen');
    Route::get('/riwayat-absen', 'riwayatAbsen')->name('riwayat_absen');
    Route::get('/profil', 'profile')->name('profile');
    Route::post('/profil-update', 'updateProfile')->name('profile.update');
    Route::get('/get-my-address', 'getMyAddress')->name('getMyAddress');
    Route::get('/detail-absensi/{id}', [AdminController::class, 'getDetailAbsen'])->name('detail_absensi');
});

require __DIR__.'/auth.php';
