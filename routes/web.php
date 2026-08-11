<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('LandingPage.welcome');
});

// Protected Dashboard Route with Authentication Guard
Route::get('/dashboard', function () {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN UNTUK MENGAKSES DASBOR.');
    }
    return view('Dashboard.index');
});

Route::get('/register', function () {
    if (Auth::check() || session('user')) {
        return redirect('/dashboard');
    }
    return view('Auth.register');
});

Route::post('/register', function (Request $request) {
    // 1. Input Validation & Database Unique Email Check
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ], [
        'name.required' => 'NAMA LENGKAP WAJIB DIISI.',
        'email.required' => 'ALAMAT EMAIL WAJIB DIISI.',
        'email.email' => 'FORMAT EMAIL TIDAK VALID.',
        'email.unique' => 'EMAIL SUDAH TERDAFTAR DI DATABASE! SILAKAN LOGIN.',
        'password.required' => 'KATA SANDI WAJIB DIISI.',
        'password.min' => 'KATA SANDI MINIMAL 6 KARAKTER.',
        'password.confirmed' => 'KONFIRMASI KATA SANDI TIDAK COCOK.',
    ]);

    // 2. Create and Save User Record into Database
    $user = User::create([
        'name' => $request->name,
        'email' => strtolower($request->email),
        'password' => Hash::make($request->password),
    ]);

    return redirect('/login')->with('success', 'REGISTRASI BERHASIL! DATA TERSIMPAN DI DATABASE. SILAKAN MASUK.');
});

Route::get('/login', function () {
    if (Auth::check() || session('user')) {
        return redirect('/dashboard');
    }
    return view('Auth.login');
});

Route::post('/login', function (Request $request) {
    // 1. Input Validation
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ], [
        'email.required' => 'ALAMAT EMAIL WAJIB DIISI.',
        'password.required' => 'KATA SANDI WAJIB DIISI.',
    ]);

    $email = strtolower($request->email);

    // 2. Database User Query
    $user = User::where('email', $email)->first();

    if (!$user) {
        return back()->with('error', 'AKUN EMAIL BELUM TERDAFTAR DI DATABASE! SILAKAN REGISTRASI TERLEBIH DAHULU.');
    }

    // 3. Password Hash Check from Database Record
    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'KATA SANDI SALAH! DITOLAK OLEH DATABASE.');
    }

    // 4. Authenticate Laravel Auth & Session
    Auth::login($user);
    $userName = strtoupper($user->name);
    session(['user' => $userName, 'user_email' => $user->email]);

    return redirect('/dashboard')->with('success', 'LOGIN BERHASIL! SESI LATIHAN DIAKTIFKAN.');
});

Route::get('/logout', function () {
    Auth::logout();
    session()->forget(['user', 'user_email']);
    return redirect('/')->with('info', 'SESI KELUAR BERHASIL.');
});
