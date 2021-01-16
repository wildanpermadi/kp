<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\LegalisirController;
use App\Http\Controllers\PelegalisirController;
use App\Http\Controllers\PermintaanLegalisirController;

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

Route::get('/test', function() {
    return view('admin.legalisir.cetak');
});

// Pages
Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [PagesController::class, 'getIndex']);
    Route::get('/profile', [PagesController::class, 'getProfile']);
    Route::patch('/profile', [PagesController::class, 'updateProfile']);
    Route::patch('/change-password', [PagesController::class, 'doChangePassword']);
});

// Auth
Route::group(['middleware' => 'guest'], function() {
    Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'doLogin']);
});
Route::group(['middleware' => 'auth'], function() {
    Route::get('/logout', [AuthController::class, 'doLogout']);
});

// Alumni
Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::prefix('alumni')->group(function() {
        Route::get('/', [AlumniController::class, 'index']);
        Route::any('/json', [AlumniController::class, 'alumniJson']);
        Route::get('/create', [AlumniController::class, 'create']);
        Route::post('/store', [AlumniController::class, 'store']);
        Route::post('/import', [AlumniController::class, 'doImport']);
        Route::get('/{alumni}', [AlumniController::class, 'show']);
        Route::get('/edit/{alumni}', [AlumniController::class, 'edit']);
        Route::patch('/update/{alumni}', [AlumniController::class, 'update']);
        Route::delete('/destroy/{alumni}', [AlumniController::class, 'destroy']);
    });
});

// Permintaan Legalisir
Route::prefix('permintaan-legalisir')->group(function() {
    Route::group(['middleware' => ['auth', 'role:admin']], function() {
        Route::get('/', [PermintaanLegalisirController::class, 'index']);
        Route::any('/json', [PermintaanLegalisirController::class, 'permintaanLegalisirJson']);
    });
    Route::group(['middleware' => ['auth', 'role:alumni']], function() {
        //Route::group(['middleware' => 'permintaan_legalisir.check'], function() {
            Route::get('/create', [PermintaanLegalisirController::class, 'create']);
            Route::post('/store', [PermintaanLegalisirController::class, 'store']);
        //});
        Route::get('/edit/{permintaan_legalisir}', [PermintaanLegalisirController::class, 'edit']);
        Route::put('/update/{permintaan_legalisir}', [PermintaanLegalisirController::class, 'update']);
        Route::delete('/destroy/{permintaan_legalisir}', [PermintaanLegalisirController::class, 'destroy']);
    });
    Route::group(['middleware' => ['auth', 'role:alumni,admin']], function() {
        Route::get('/{permintaan_legalisir}', [PermintaanLegalisirController::class, 'show']);
    });
});

// Legalisir
Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::prefix('legalisir')->group(function() {
        Route::get('/', [LegalisirController::class, 'index']);
        Route::any('/json', [LegalisirController::class, 'legalisirJson']);
        Route::get('/create/{permintaan_legalisir}', [LegalisirController::class, 'create']);
        Route::post('/store', [LegalisirController::class, 'store']);
        Route::get('/{legalisir}', [LegalisirController::class, 'show']);
        Route::patch('/update/{legalisir}', [LegalisirController::class, 'update']);
    });
});

// Pelegalisir
Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::prefix('pelegalisir')->group(function() {
        Route::get('/', [PelegalisirController::class, 'index']);
        Route::any('/json', [PelegalisirController::class, 'pelegalisirJson']);
        Route::get('/create', [PelegalisirController::class, 'create']);
        Route::post('/store', [PelegalisirController::class, 'store']);
        Route::get('/{pelegalisir}', [PelegalisirController::class, 'show']);
        Route::get('/edit/{pelegalisir}', [PelegalisirController::class, 'edit']);
        Route::put('/update/{pelegalisir}', [PelegalisirController::class, 'update']);
        Route::delete('/destroy/{pelegalisir}', [PelegalisirController::class, 'destroy']);
    });
});
