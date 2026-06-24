<?php

use App\Http\Controllers\PengaduanController;
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

Route::get('/', function () {
    // return view('welcome');
    return view('welcome');
});

Route::get('/lapor', [PengaduanController::class, 'index'])->name('pengaduan.index');
Route::post('/lapor', [PengaduanController::class, 'store'])->name('pengaduan.store');
Route::post('/upload-temp-lampiran', [PengaduanController::class, 'uploadTempLampiran'])->name('pengaduan.upload-temp-lampiran');
Route::post('/upload-temp-lampiran-clear', [PengaduanController::class, 'uploadTempLampiranClear'])->name('pengaduan.clear-temp-lampiran');
Route::delete('/upload-clear-refresh', [PengaduanController::class, 'uploadClearRefresh'])->name('pengaduan.clear-temp-lampiran.refresh');




Auth::routes();


//route untuk yang sudah login
Route::middleware(['auth'])->group(function () {
   Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
   Route::get('/notifikasi-laporan', [PengaduanController::class, 'notifikasi'])->name('pengaduan.notifikasi');
   Route::get('/data-laporan', [PengaduanController::class, 'dataPengaduan'])->name('data-pengaduan');
   Route::get('/data-laporan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');
   Route::get('/data-laporan/{pengaduan}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
   Route::put('/data-laporan/{pengaduan}', [PengaduanController::class, 'update'])->name('pengaduan.update');
   Route::delete('/data-laporan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');

   // Pengelolaan User
   // index/edit/update terbuka untuk semua user yang login (otorisasi diatur di controller:
   // non-admin hanya boleh data sendiri, admin boleh semua)
   Route::get('/data-user', [UserController::class, 'index'])->name('user.index');
   Route::get('/data-user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
   Route::put('/data-user/{user}', [UserController::class, 'update'])->name('user.update');

   // create/store/destroy hanya untuk admin
   Route::middleware('can:manage-users')->group(function () {
      Route::get('/data-user/create', [UserController::class, 'create'])->name('user.create');
      Route::post('/data-user', [UserController::class, 'store'])->name('user.store');
      Route::delete('/data-user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
   });
});
