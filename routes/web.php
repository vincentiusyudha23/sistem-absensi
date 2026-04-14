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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(AdminController::class)->prefix('admin')->name('admin.')->group(function(){
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/daftar-anggota', 'listUsers')->name('list_users');
        Route::get('/tambah-anggota', 'tambahAnggota')->name('tambah_anggota');
        Route::post('/tambah-anggota/store', 'storeAnggota')->name('tambah_anggota.store');
        Route::get('/edit-anggota/{id}', 'editAnggota')->name('edit_anggota');
        Route::post('/edit-anggota/update/{id}', 'updateAnggota')->name('edit_anggota.update');
        Route::get('/data-absensi', 'dataAbsensi')->name('data_absensi');
    });

    Route::controller(UserController::class)->prefix('anggota')->name('user.')->group(function(){
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::post('/store-absen', 'storeAbsen')->name('store_absen');
        Route::get('/riwayat-absen', 'riwayatAbsen')->name('riwayat_absen');
    });
});

require __DIR__.'/auth.php';
