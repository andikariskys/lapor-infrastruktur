<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\UserController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('api.auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ReportController::class, 'dashboard'])->name('dashboard');

    Route::get('/laporan', [ReportController::class, 'index']);
    Route::get('/laporan/{id}', [ReportController::class, 'show']);
    Route::patch('/laporan/{id}', [ReportController::class, 'update']);

    Route::get('/kategori', [CategoryController::class, 'index']);
    Route::post('/kategori', [CategoryController::class, 'store']);
    Route::patch('/kategori/{id}', [CategoryController::class, 'update']);
    Route::delete('/kategori/{id}', [CategoryController::class, 'destroy']);

    Route::get('/lembaga', [InstitutionController::class, 'index']);
    Route::post('/lembaga', [InstitutionController::class, 'store']);
    Route::patch('/lembaga/{id}', [InstitutionController::class, 'update']);
    Route::delete('/lembaga/{id}', [InstitutionController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/officers', [UserController::class, 'storeOfficer']);
    Route::patch('/users/{id}', [UserController::class, 'update']);
    Route::patch('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/profil', function () {
        return view('profil');
    });
    Route::patch('/profil', [\App\Http\Controllers\ProfileController::class, 'update']);
    Route::patch('/profil/password', [\App\Http\Controllers\ProfileController::class, 'changePassword']);
});
