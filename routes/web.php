<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('LandingPage.welcome');
});

Route::get('/register', function () {
    return view('Auth.register');
});

Route::post('/register', function (Request $request) {
    // Process registration & redirect to login with success flash alert
    return redirect('/login')->with('success', 'REGISTRASI BERHASIL! SILAKAN MASUK KE AKUN ANDA.');
});

Route::get('/login', function () {
    return view('Auth.login');
});

Route::post('/login', function (Request $request) {
    // Process login & redirect to landing page with active user session
    $userName = strtoupper(explode('@', $request->input('email', 'ZAKI.Y'))[0]);
    return redirect('/')->with('user', $userName)->with('success', 'LOGIN BERHASIL! SESI LATIHAN DIAKTIFKAN.');
});

Route::get('/logout', function () {
    return redirect('/')->with('info', 'SESI KELUAR BERHASIL.');
});
