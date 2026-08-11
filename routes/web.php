<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\WorkoutLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('LandingPage.welcome');
});

// Helper function to get authenticated user ID
function getAuthUserId() {
    if (Auth::check()) return Auth::id();
    if (session('user_email')) {
        $user = User::where('email', session('user_email'))->first();
        if ($user) {
            Auth::login($user);
            return $user->id;
        }
    }
    return null;
}

// 1. Dashboard Overview Route
Route::get('/dashboard', function () {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN UNTUK MENGAKSES DASBOR.');
    }

    $userId = getAuthUserId();
    $schedules = Schedule::where('user_id', $userId)->get();
    
    // Map today's Indonesian day name
    $dayMap = [
        'Monday' => 'SENIN',
        'Tuesday' => 'SELASA',
        'Wednesday' => 'RABU',
        'Thursday' => 'KAMIS',
        'Friday' => 'JUMAT',
        'Saturday' => 'SABTU',
        'Sunday' => 'MINGGU'
    ];
    $todayName = $dayMap[date('l')] ?? 'SENIN';
    $todaySchedule = $schedules->where('day_name', $todayName)->first();

    // Fetch total logs count and volume for overview stats
    $totalLogs = WorkoutLog::where('user_id', $userId)->count();
    $totalDaysSet = $schedules->count();
    $totalRestDays = $schedules->where('is_rest', true)->count();
    $totalWorkoutDays = $totalDaysSet - $totalRestDays;

    return view('Dashboard.overview', compact('schedules', 'todayName', 'todaySchedule', 'totalDaysSet', 'totalRestDays', 'totalWorkoutDays', 'totalLogs'));
});

// 2. Workout Schedule Route (Routine Setup per Day)
Route::get('/dashboard/schedule', function () {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();
    $days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
    $schedules = Schedule::where('user_id', $userId)->get()->keyBy('day_name');

    // Days not yet added
    $addedDays = $schedules->keys()->toArray();
    $availableDays = array_diff($days, $addedDays);

    $totalDaysSet = $schedules->count();
    $totalRestDays = $schedules->where('is_rest', true)->count();
    $totalWorkoutDays = $totalDaysSet - $totalRestDays;

    return view('Dashboard.schedule', compact('days', 'schedules', 'availableDays', 'totalDaysSet', 'totalRestDays', 'totalWorkoutDays'));
});

// 3. Date-based Workout Log Route (/dashboard/logs)
Route::get('/dashboard/logs', function (Request $request) {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();

    // Selected date (defaults to today's date Y-m-d)
    $selectedDate = $request->query('date', date('Y-m-d'));

    // Indonesian Day name mapping for selected date
    $dayNameEn = date('l', strtotime($selectedDate));
    $dayMap = [
        'Monday' => 'SENIN',
        'Tuesday' => 'SELASA',
        'Wednesday' => 'RABU',
        'Thursday' => 'KAMIS',
        'Friday' => 'JUMAT',
        'Saturday' => 'SABTU',
        'Sunday' => 'MINGGU'
    ];
    $dayNameId = $dayMap[$dayNameEn] ?? 'SENIN';

    // Find schedule for this day
    $scheduledRoutine = Schedule::where('user_id', $userId)->where('day_name', $dayNameId)->first();

    // Fetch logs for this date
    $logs = WorkoutLog::where('user_id', $userId)->where('log_date', $selectedDate)->orderBy('id', 'asc')->get();

    // Compute stats for selected date
    $totalExercises = $logs->count();
    $totalSets = $logs->sum('sets');
    $totalVolumeKg = $logs->sum(function($item) {
        return $item->sets * $item->reps * $item->weight_kg;
    });

    return view('Dashboard.logs', compact('selectedDate', 'dayNameId', 'scheduledRoutine', 'logs', 'totalExercises', 'totalSets', 'totalVolumeKg'));
});

// Save Workout Log Entry
Route::post('/dashboard/logs', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $request->validate([
        'log_date' => 'required|date',
        'exercise_name' => 'required|string|max:255',
        'sets' => 'required|integer|min:1',
        'reps' => 'required|integer|min:1',
        'weight_kg' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ], [
        'log_date.required' => 'TANGGAL WAJIB DIISI.',
        'exercise_name.required' => 'NAMA LATIHAN WAJIB DIISI.',
        'sets.required' => 'JUMLAH SET WAJIB DIISI.',
        'reps.required' => 'REPETISI WAJIB DIISI.',
        'weight_kg.required' => 'BEBAN (KG) WAJIB DIISI.',
    ]);

    $dayNameEn = date('l', strtotime($request->log_date));
    $dayMap = [
        'Monday' => 'SENIN',
        'Tuesday' => 'SELASA',
        'Wednesday' => 'RABU',
        'Thursday' => 'KAMIS',
        'Friday' => 'JUMAT',
        'Saturday' => 'SABTU',
        'Sunday' => 'MINGGU'
    ];
    $dayNameId = $dayMap[$dayNameEn] ?? 'SENIN';

    $scheduledRoutine = Schedule::where('user_id', $userId)->where('day_name', $dayNameId)->first();
    $routineTitle = $scheduledRoutine ? $scheduledRoutine->title : ($request->routine_title ?? 'SESI BESOK/LATIHAN BEBAS');

    WorkoutLog::create([
        'user_id' => $userId,
        'log_date' => $request->log_date,
        'routine_title' => strtoupper($routineTitle),
        'exercise_name' => strtoupper($request->exercise_name),
        'sets' => $request->sets,
        'reps' => $request->reps,
        'weight_kg' => $request->weight_kg,
        'notes' => $request->notes,
    ]);

    return redirect('/dashboard/logs?date=' . $request->log_date)->with('success', 'CATATAN LATIHAN ' . strtoupper($request->exercise_name) . ' BERHASIL DISIMPAN!');
});

// Delete Workout Log Entry
Route::post('/dashboard/logs/delete', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $log = WorkoutLog::where('user_id', $userId)->where('id', $request->log_id)->first();
    if ($log) {
        $date = $log->log_date;
        $log->delete();
        return redirect('/dashboard/logs?date=' . $date)->with('info', 'CATATAN LATIHAN BERHASIL DIHAPUS.');
    }

    return back()->with('error', 'LOG TIDAK DITEMUKAN.');
});

// Create or Update Schedule Item
Route::post('/schedules', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $request->validate([
        'day_name' => 'required|string',
        'title' => 'required|string|max:255',
        'focus_target' => 'nullable|string|max:255',
    ], [
        'day_name.required' => 'HARI WAJIB DIPILIH.',
        'title.required' => 'NAMA/NAMA SESI LATIHAN WAJIB DIISI.',
    ]);

    $isRest = $request->has('is_rest') || strtoupper($request->title) === 'REST DAY' || strtoupper($request->title) === 'ISTIRAHAT';

    Schedule::updateOrCreate(
        [
            'user_id' => $userId,
            'day_name' => strtoupper($request->day_name),
        ],
        [
            'title' => strtoupper($request->title),
            'focus_target' => $request->focus_target ? strtoupper($request->focus_target) : null,
            'is_rest' => $isRest,
        ]
    );

    $redirectUrl = $request->input('redirect_to', '/dashboard/schedule');
    return redirect($redirectUrl)->with('success', 'JADWAL LATIHAN HARI ' . strtoupper($request->day_name) . ' BERHASIL DISIMPAN!');
});

// Delete Schedule Entry for a specific Day
Route::post('/schedules/delete', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $dayName = strtoupper($request->day_name);
    Schedule::where('user_id', $userId)->where('day_name', $dayName)->delete();

    $redirectUrl = $request->input('redirect_to', '/dashboard/schedule');
    return redirect($redirectUrl)->with('info', 'JADWAL HARI ' . $dayName . ' BERHASIL DIHAPUS.');
});

Route::get('/register', function () {
    if (Auth::check() || session('user')) return redirect('/dashboard');
    return view('Auth.register');
});

Route::post('/register', function (Request $request) {
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

    $user = User::create([
        'name' => $request->name,
        'email' => strtolower($request->email),
        'password' => Hash::make($request->password),
    ]);

    return redirect('/login')->with('success', 'REGISTRASI BERHASIL! DATA TERSIMPAN DI DATABASE. SILAKAN MASUK.');
});

Route::get('/login', function () {
    if (Auth::check() || session('user')) return redirect('/dashboard');
    return view('Auth.login');
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ], [
        'email.required' => 'ALAMAT EMAIL WAJIB DIISI.',
        'password.required' => 'KATA SANDI WAJIB DIISI.',
    ]);

    $email = strtolower($request->email);
    $user = User::where('email', $email)->first();

    if (!$user) {
        return back()->with('error', 'AKUN EMAIL BELUM TERDAFTAR DI DATABASE! SILAKAN REGISTRASI TERLEBIH DAHULU.');
    }

    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'KATA SANDI SALAH! DITOLAK OLEH DATABASE.');
    }

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
