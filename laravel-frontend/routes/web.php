<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/laporan', function () {
    return view('laporan');
});

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
