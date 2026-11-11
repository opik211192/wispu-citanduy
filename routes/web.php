<?php

use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Services\WhatsAppService;

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
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf-token');
Route::post('/lapor', [PengaduanController::class, 'store'])->name('pengaduan.store');
Route::post('/upload-temp-lampiran', [PengaduanController::class, 'uploadTempLampiran'])->name('pengaduan.upload-temp-lampiran');
Route::post('/upload-temp-lampiran-clear', [PengaduanController::class, 'uploadTempLampiranClear'])->name('pengaduan.clear-temp-lampiran');
Route::delete('/upload-clear-refresh', [PengaduanController::class, 'uploadClearRefresh'])->name('pengaduan.clear-temp-lampiran.refresh');

// ================= FAQ =================
Route::get('/faq', function () {
    return view('faq');
})->name('faq');



// Registrasi mandiri dinonaktifkan — akun hanya dibuat oleh admin
Auth::routes(['register' => false]);


//route untuk yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/notifikasi-laporan', [PengaduanController::class, 'notifikasi'])->name('pengaduan.notifikasi');
    Route::get('/data-laporan', [PengaduanController::class, 'dataPengaduan'])->name('data-pengaduan');
    Route::get('/data-laporan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');
    Route::get('/data-laporan/{pengaduan}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
    Route::put('/data-laporan/{pengaduan}', [PengaduanController::class, 'update'])->name('pengaduan.update');
    Route::delete('/data-laporan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    Route::post('/data-laporan/{pengaduan}/tindak-lanjut', [PengaduanController::class, 'tindakLanjut'])
        ->name('pengaduan.tindak-lanjut');
    Route::post('/pengaduan/{pengaduan}/ditolak', 
    [PengaduanController::class, 'ditolak'])
        ->name('pengaduan.ditolak');
    Route::get('/data-laporan/{pengaduan}/print', [PengaduanController::class, 'print'])
        ->name('pengaduan.print');   

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


//WA 

Route::get('/test-watzap', function (WhatsAppService $whatsapp) {

    $response = $whatsapp->sendMessage(
        '6282120837043',
        'Halo, ini adalah pesan percobaan dari aplikasi Pengaduan Laravel.'
    );

    return response()->json([
        'status_code' => $response->status(),
        'response' => $response->json(),
    ]);
});
