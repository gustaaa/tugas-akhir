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
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\ProduksiController::class, 'index'])->name('index');
Route::resource('user', UserController::class);
Route::get('/laporan/user', [UserController::class, 'laporan']);
Route::post('/data-import', [UserController::class, 'import'])->name('user.import');
Route::get('export-user', [UserController::class, 'export'])->name('user.export');

route::resource('produksi', AlternatifController::class);
Route::post('/data-import', [AlternatifController::class, 'import']);
Route::get('export', [AlternatifController::class, 'export'])->name('produksi.export');
Route::post('/produksi/clear', [AlternatifController::class, 'clear'])->name('produksi.clear');

route::resource('prediksi', ProduksiController::class);

Route::get('coba', [ProduksiController::class, 'moorePenroseInverse'])->name('coba');
Route::get('test', [ProduksiController::class, 'calculateDeterminant'])->name('test');
route::get('normalisasi', [ProduksiController::class, 'normalisasi'])->name('normalisasi');
Route::post('/bobot', [ProduksiController::class, 'parameter'])->name('parameter');
route::get('/bobot', [ProduksiController::class, 'parameter'])->name('parameter');
Route::post('/predict', [ProduksiController::class, 'predict'])->name('predict');
route::get('/predict', [ProduksiController::class, 'predict'])->name('predict');
Route::post('/perkalian', [ProduksiController::class, 'perkalian_matriks'])->name('perkalian_matriks');
route::get('/perkalian', [ProduksiController::class, 'perkalian_matriks'])->name('perkalian_matriks');
Route::post('/evaluasi', [ProduksiController::class, 'mape'])->name('mape');
route::get('/evaluasi', [ProduksiController::class, 'mape'])->name('mape');
Route::post('/denormalisasi', [ProduksiController::class, 'denormalisasi'])->name('denormalisasi');
route::get('/denormalisasi', [ProduksiController::class, 'denormalisasi'])->name('denormalisasi');

Route::post('/data-training', [ProduksiController::class, 'training'])->name('training');
route::get('/data-training', [ProduksiController::class, 'training'])->name('training');
Route::post('/data-testing', [ProduksiController::class, 'testing'])->name('testing');
route::get('/data-testing', [ProduksiController::class, 'testing'])->name('testing');
