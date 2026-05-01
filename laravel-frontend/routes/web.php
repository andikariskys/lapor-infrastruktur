<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('api.auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ReportController::class, 'dashboard'])->name('dashboard');

    Route::get('/laporan', [ReportController::class, 'index']);
    Route::get('/laporan/{id}', [ReportController::class, 'show']);

    Route::get('/laporan/detail', function () {
        return view('laporan-detail');
    });

    Route::get('/kategori', function () {
        return view('kategori');
    });

    Route::get('/lembaga', function () {
        return view('lembaga');
    });

    Route::get('/users', function () {
        return view('users');
    });

    Route::get('/profil', function () {
        return view('profil');
    });
});
