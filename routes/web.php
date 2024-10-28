<?php

use App\Http\Controllers\PengaduanController;
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
   Route::get('/data-laporan', [PengaduanController::class, 'dataPengaduan'])->name('data-pengaduan'); 
});
