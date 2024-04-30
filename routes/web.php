<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\AlternatifController;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('user', UserController::class);
Route::get('/laporan/user', [UserController::class, 'laporan']);
Route::post('/data-import', [UserController::class, 'import'])->name('user.import');
Route::get('export-user', [UserController::class, 'export'])->name('user.export');

route::resource('produksi', AlternatifController::class);
Route::post('/data-import', [AlternatifController::class, 'import']);
Route::get('export', [AlternatifController::class, 'export'])->name('produksi.export');
Route::post('/produksi/clear', [AlternatifController::class, 'clear'])->name('produksi.clear');

route::get('normalisasi', [ProduksiController::class, 'normalisasi'])->name('normalisasi');
Route::post('/bobot', [ProduksiController::class, 'bobotMatriks'])->name('bobotMatriks');
route::get('/bobot', [ProduksiController::class, 'bobotMatriks'])->name('bobotMatriks');
Route::post('/data', [ProduksiController::class, 'splitDataAndView'])->name('splitDataAndView');
route::get('/data', [ProduksiController::class, 'splitDataAndView'])->name('splitDataAndView');
Route::post('/perkalian', [ProduksiController::class, 'perkalian_matriks'])->name('perkalian_matriks');
route::get('/perkalian', [ProduksiController::class, 'perkalian_matriks'])->name('perkalian_matriks');
Route::post('/fungsi', [ProduksiController::class, 'fungsiAktivasi'])->name('fungsiAktivasi');
route::get('/fungsi', [ProduksiController::class, 'fungsiAktivasi'])->name('fungsiAktivasi');
Route::post('/moore', [ProduksiController::class, 'moorePenroseInverse'])->name('moorePenroseInverse');
route::get('/moore', [ProduksiController::class, 'moorePenroseInverse'])->name('moorePenroseInverse');
