<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', function () {
    return redirect('/warga/home');
});

Route::get('/warga/home', function () {
    return view('warga.home');
});

Route::get('/warga/unduh', function () {
    return view('warga.unduh');
});

Route::get('/warga/pengurusan', function () {
    return view('warga.pengurusan');
});

Route::post('/warga/pengurusan/submit', function () {
    // TODO: Proses upload file dan simpan ke database
    return redirect('/warga/home')->with('success', 'Pengajuan surat berhasil dikirim');
});

// Petugas Routes
Route::get('/petugas/home', function () {
    return view('petugas.home');
});

Route::get('/petugas/daftar', function () {
    return view('petugas.daftar-pengajuan');
});

Route::get('/petugas/tanda-tangan', function () {
    return view('petugas.tanda-tangan');
});
