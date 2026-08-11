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

// Helper function to format Y-m to Indonesian Month Label e.g. "2026-08" -> "AGUSTUS 2026"
function getIndonesianMonthLabel($monthYear) {
    $parts = explode('-', $monthYear);
    if (count($parts) !== 2) return strtoupper($monthYear);
    
    $year = $parts[0];
    $month = (int)$parts[1];

    $monthsId = [
        1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
        5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
        9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
    ];

    return ($monthsId[$month] ?? 'BULAN') . ' ' . $year;
}

// Helper function to get today's Indonesian Day Name & Schedule Title
function getTodayInfo($userId) {
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
    $currentMonthYear = date('Y-m');

    $sched = Schedule::where('user_id', $userId)
        ->where('month_year', $currentMonthYear)
        ->where('day_name', $todayName)
        ->first();

    $routineTitle = $sched ? $sched->title : 'BELUM ATUR JADWAL';

    return [
        'todayName' => $todayName,
        'todayDateFormatted' => date('d/m/Y'),
        'todayRoutineTitle' => $routineTitle,
        'todaySchedule' => $sched,
    ];
}

// 1. Dashboard Overview Route
Route::get('/dashboard', function () {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN UNTUK MENGAKSES DASBOR.');
    }

    $userId = getAuthUserId();
    $currentMonthYear = date('Y-m');
    $currentMonthLabel = getIndonesianMonthLabel($currentMonthYear);
    $schedules = Schedule::where('user_id', $userId)->where('month_year', $currentMonthYear)->get();
    
    $todayInfo = getTodayInfo($userId);
    $todayName = $todayInfo['todayName'];
    $todaySchedule = $todayInfo['todaySchedule'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    $totalLogs = WorkoutLog::where('user_id', $userId)->count();
    $totalDaysSet = $schedules->count();
    $totalRestDays = $schedules->where('is_rest', true)->count();
    $totalWorkoutDays = $totalDaysSet - $totalRestDays;

    return view('Dashboard.overview', compact('schedules', 'todayName', 'todaySchedule', 'todayRoutineTitle', 'totalDaysSet', 'totalRestDays', 'totalWorkoutDays', 'totalLogs', 'currentMonthLabel'));
});

// 2. Workout Schedule Route with Monthly Program Selection
Route::get('/dashboard/schedule', function (Request $request) {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();
    $days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];

    $selectedMonth = $request->query('month', date('Y-m'));
    $monthLabel = getIndonesianMonthLabel($selectedMonth);

    $schedules = Schedule::where('user_id', $userId)->where('month_year', $selectedMonth)->get()->keyBy('day_name');

    $existingMonths = Schedule::where('user_id', $userId)->pluck('month_year')->toArray();
    $defaultMonths = [];
    for ($i = 0; $i < 6; $i++) {
        $defaultMonths[] = date('Y-m', strtotime("+$i month"));
    }
    $allMonths = array_unique(array_merge($defaultMonths, $existingMonths));
    sort($allMonths);

    $todayInfo = getTodayInfo($userId);
    $todayName = $todayInfo['todayName'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    $totalDaysSet = $schedules->count();
    $totalRestDays = $schedules->where('is_rest', true)->count();
    $totalWorkoutDays = $totalDaysSet - $totalRestDays;

    return view('Dashboard.schedule', compact('days', 'schedules', 'selectedMonth', 'monthLabel', 'allMonths', 'todayName', 'todayRoutineTitle', 'totalDaysSet', 'totalRestDays', 'totalWorkoutDays'));
});

// 3. Date-based Workout Log Route (/dashboard/logs)
Route::get('/dashboard/logs', function (Request $request) {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();

    $selectedDate = $request->query('date', date('Y-m-d'));
    $selectedMonthYear = date('Y-m', strtotime($selectedDate));
    $selectedMonthLabel = getIndonesianMonthLabel($selectedMonthYear);

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

    $scheduledRoutine = Schedule::where('user_id', $userId)
        ->where('month_year', $selectedMonthYear)
        ->where('day_name', $dayNameId)
        ->first();

    $logs = WorkoutLog::where('user_id', $userId)->where('log_date', $selectedDate)->orderBy('id', 'asc')->get();

    $todayInfo = getTodayInfo($userId);
    $todayName = $todayInfo['todayName'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    $totalExercises = $logs->count();
    $totalSets = $logs->sum('sets');
    $totalVolumeKg = $logs->sum(function($item) {
        return $item->sets * $item->reps * $item->weight_kg;
    });

    return view('Dashboard.logs', compact('selectedDate', 'selectedMonthLabel', 'dayNameId', 'scheduledRoutine', 'logs', 'todayName', 'todayRoutineTitle', 'totalExercises', 'totalSets', 'totalVolumeKg'));
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

    $logMonthYear = date('Y-m', strtotime($request->log_date));
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

    $scheduledRoutine = Schedule::where('user_id', $userId)
        ->where('month_year', $logMonthYear)
        ->where('day_name', $dayNameId)
        ->first();

    $routineTitle = $scheduledRoutine ? $scheduledRoutine->title : ($request->routine_title ?? 'LATIHAN BEBAS');

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
        'month_year' => 'required|string',
        'day_name' => 'required|string',
        'title' => 'required|string|max:255',
        'focus_target' => 'nullable|string|max:255',
    ], [
        'day_name.required' => 'HARI WAJIB DIPILIH.',
        'title.required' => 'NAMA/NAMA SESI LATIHAN WAJIB DIISI.',
    ]);

    $monthYear = $request->month_year;
    $dayName = strtoupper($request->day_name);
    $isRest = $request->has('is_rest') || strtoupper($request->title) === 'REST DAY' || strtoupper($request->title) === 'ISTIRAHAT';

    Schedule::updateOrCreate(
        [
            'user_id' => $userId,
            'month_year' => $monthYear,
            'day_name' => $dayName,
        ],
        [
            'title' => strtoupper($request->title),
            'focus_target' => $request->focus_target ? strtoupper($request->focus_target) : null,
            'is_rest' => $isRest,
        ]
    );

    $redirectUrl = '/dashboard/schedule?month=' . $monthYear;
    return redirect($redirectUrl)->with('success', 'JADWAL LATIHAN ' . $dayName . ' (' . getIndonesianMonthLabel($monthYear) . ') BERHASIL DISIMPAN!');
});

// Delete Schedule Entry
Route::post('/schedules/delete', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $monthYear = $request->month_year;
    $dayName = strtoupper($request->day_name);
    
    Schedule::where('user_id', $userId)
        ->where('month_year', $monthYear)
        ->where('day_name', $dayName)
        ->delete();

    $redirectUrl = '/dashboard/schedule?month=' . $monthYear;
    return redirect($redirectUrl)->with('info', 'JADWAL HARI ' . $dayName . ' (' . getIndonesianMonthLabel($monthYear) . ') BERHASIL DIHAPUS.');
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
