<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\WorkoutLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

// Helper function to calculate total volumetric load from a collection of logs
function calculateLogsVolume($logs) {
    return $logs->sum(function($item) {
        return $item->sets * $item->reps * $item->weight_kg;
    });
}

// Helper to compute percentage change
function computePercentDiff($current, $previous) {
    if ($previous == 0) {
        return $current > 0 ? 100.0 : 0.0;
    }
    return round((($current - $previous) / $previous) * 100, 1);
}

// 1. Dashboard Overview Route (/dashboard)
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
    $todayDate = date('Y-m-d');
    $todaySchedule = $todayInfo['todaySchedule'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    $allLogs = WorkoutLog::where('user_id', $userId)->get();
    $totalLogs = $allLogs->count();
    $allTimeVol = calculateLogsVolume($allLogs);

    $todayLogs = WorkoutLog::where('user_id', $userId)->where('log_date', $todayDate)->get();
    $todayVol = calculateLogsVolume($todayLogs);
    $todaySets = $todayLogs->sum('sets');

    $totalDaysSet = $schedules->count();
    $totalRestDays = $schedules->where('is_rest', true)->count();
    $totalWorkoutDays = $totalDaysSet - $totalRestDays;

    $recentLogs = WorkoutLog::where('user_id', $userId)
        ->orderBy('log_date', 'desc')
        ->orderBy('id', 'desc')
        ->take(5)
        ->get();

    return view('Dashboard.overview', compact(
        'schedules', 'todayName', 'todaySchedule', 'todayRoutineTitle',
        'totalDaysSet', 'totalRestDays', 'totalWorkoutDays', 'totalLogs',
        'currentMonthLabel', 'allTimeVol', 'todayVol', 'todaySets', 'recentLogs'
    ));
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

// Export Schedule to Styled Excel Document
Route::get('/dashboard/schedule/export-excel', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $selectedMonth = $request->query('month', date('Y-m'));
    $monthLabel = getIndonesianMonthLabel($selectedMonth);
    $schedules = Schedule::where('user_id', $userId)->where('month_year', $selectedMonth)->get();
    $userName = session('user', 'USER NAOOLIFT');

    $fileName = 'NaooLift_Jadwal_Program_' . str_replace(' ', '_', $monthLabel) . '.xls';

    $headers = [
        "Content-type" => "application/vnd.ms-excel; charset=utf-8",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $html = view('Exports.schedule_excel', compact('schedules', 'monthLabel', 'userName'))->render();

    return response($html, 200, $headers);
});

// 3. Date-based and Multi-Period Workout Log Route (/dashboard/logs)
Route::get('/dashboard/logs', function (Request $request) {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();

    $activeView = $request->query('view', 'daily'); // 'daily', 'weekly', 'monthly', 'all'
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

    $query = WorkoutLog::where('user_id', $userId);

    if ($activeView === 'daily') {
        $query->where('log_date', $selectedDate)->orderBy('id', 'asc');
        $viewLabel = 'CATATAN LATIHAN TANGGAL ' . date('d/m/Y', strtotime($selectedDate));
    } elseif ($activeView === 'weekly') {
        $mondayThisWeek = date('Y-m-d', strtotime('monday this week'));
        $sundayThisWeek = date('Y-m-d', strtotime('sunday this week'));
        $query->whereBetween('log_date', [$mondayThisWeek, $sundayThisWeek])->orderBy('log_date', 'desc')->orderBy('id', 'desc');
        $viewLabel = 'SEMUA CATATAN LATIHAN MINGGU INI (' . date('d/m', strtotime($mondayThisWeek)) . ' - ' . date('d/m/Y', strtotime($sundayThisWeek)) . ')';
    } elseif ($activeView === 'monthly') {
        $firstDayThisMonth = date('Y-m-01');
        $lastDayThisMonth = date('Y-m-t');
        $query->whereBetween('log_date', [$firstDayThisMonth, $lastDayThisMonth])->orderBy('log_date', 'desc')->orderBy('id', 'desc');
        $viewLabel = 'SEMUA CATATAN LATIHAN BULAN INI (' . $selectedMonthLabel . ')';
    } else { // 'all'
        $query->orderBy('log_date', 'desc')->orderBy('id', 'desc');
        $viewLabel = 'SEMUA RIWAYAT CATATAN LATIHAN (ALL-TIME)';
    }

    $logs = $query->get();

    $todayInfo = getTodayInfo($userId);
    $todayName = $todayInfo['todayName'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    $totalExercises = $logs->count();
    $totalSets = $logs->sum('sets');
    $totalVolumeKg = calculateLogsVolume($logs);
    $totalDurationSeconds = $logs->sum('duration_seconds');

    return view('Dashboard.logs', compact(
        'activeView', 'selectedDate', 'selectedMonthLabel', 'dayNameId', 'scheduledRoutine',
        'logs', 'viewLabel', 'todayName', 'todayRoutineTitle',
        'totalExercises', 'totalSets', 'totalVolumeKg', 'totalDurationSeconds'
    ));
});

// Export Workout Logs to Styled Excel Document
Route::get('/dashboard/logs/export-excel', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $selectedDate = $request->query('date');
    $userName = session('user', 'USER NAOOLIFT');
    
    if ($selectedDate) {
        $logs = WorkoutLog::where('user_id', $userId)->where('log_date', $selectedDate)->orderBy('id', 'asc')->get();
        $fileName = 'NaooLift_Log_Latihan_' . $selectedDate . '.xls';
        $titleLabel = 'CATATAN LATIHAN TANGGAL ' . date('d/m/Y', strtotime($selectedDate));
    } else {
        $logs = WorkoutLog::where('user_id', $userId)->orderBy('log_date', 'desc')->get();
        $fileName = 'NaooLift_Semua_Log_Latihan_' . date('Y-m-d') . '.xls';
        $titleLabel = 'SEMUA CATATAN LATIHAN NAOOLIFT';
    }

    $totalVolume = calculateLogsVolume($logs);

    $headers = [
        "Content-type" => "application/vnd.ms-excel; charset=utf-8",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $html = view('Exports.workout_logs_excel', compact('logs', 'titleLabel', 'userName', 'totalVolume', 'selectedDate'))->render();

    return response($html, 200, $headers);
});

// 4. Statistics Overview Route (/dashboard/stats)
Route::get('/dashboard/stats', function () {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();
    $todayInfo = getTodayInfo($userId);
    $todayName = $todayInfo['todayName'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    $allLogs = WorkoutLog::where('user_id', $userId)->get();

    $allTimeVol = calculateLogsVolume($allLogs);
    $allTimeSets = $allLogs->sum('sets');
    $totalExercisesCount = $allLogs->count();
    $totalActiveDays = $allLogs->pluck('log_date')->unique()->count();

    // Top Executed Exercises Ranking
    $topExercises = WorkoutLog::where('user_id', $userId)
        ->select('exercise_name', DB::raw('SUM(sets * reps * weight_kg) as total_vol'), DB::raw('SUM(sets) as total_sets'), DB::raw('COUNT(*) as total_count'))
        ->groupBy('exercise_name')
        ->orderBy('total_vol', 'desc')
        ->take(5)
        ->get();

    // Recent 7 Days Volume Trend
    $recentSevenDays = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-$i day"));
        $dayLogs = $allLogs->where('log_date', $d);
        $vol = calculateLogsVolume($dayLogs);
        $recentSevenDays[] = [
            'date' => $d,
            'day_label' => date('D', strtotime($d)),
            'volume' => $vol,
            'sets' => $dayLogs->sum('sets')
        ];
    }

    return view('Dashboard.stats', compact(
        'todayName', 'todayRoutineTitle',
        'allTimeVol', 'allTimeSets', 'totalExercisesCount', 'totalActiveDays',
        'topExercises', 'recentSevenDays'
    ));
});

// 5. Dedicated Comparison Page Route (/dashboard/comparison)
Route::get('/dashboard/comparison', function (Request $request) {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();
    $todayInfo = getTodayInfo($userId);
    $todayName = $todayInfo['todayName'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    $activeMode = $request->query('mode', 'daily');

    // A. HARI INI vs KEMARIN
    $todayDate = date('Y-m-d');
    $yesterdayDate = date('Y-m-d', strtotime('-1 day'));

    $todayLogs = WorkoutLog::where('user_id', $userId)->where('log_date', $todayDate)->get();
    $yesterdayLogs = WorkoutLog::where('user_id', $userId)->where('log_date', $yesterdayDate)->get();

    $todayVol = calculateLogsVolume($todayLogs);
    $yesterdayVol = calculateLogsVolume($yesterdayLogs);
    $dailyVolDiff = $todayVol - $yesterdayVol;
    $dailyVolPercent = computePercentDiff($todayVol, $yesterdayVol);

    $todaySets = $todayLogs->sum('sets');
    $yesterdaySets = $yesterdayLogs->sum('sets');

    $todayExercises = $todayLogs->count();
    $yesterdayExercises = $yesterdayLogs->count();

    // B. MINGGU INI vs MINGGU KEMARIN
    $mondayThisWeek = date('Y-m-d', strtotime('monday this week'));
    $sundayThisWeek = date('Y-m-d', strtotime('sunday this week'));
    
    $mondayLastWeek = date('Y-m-d', strtotime('monday last week'));
    $sundayLastWeek = date('Y-m-d', strtotime('sunday last week'));

    $thisWeekLogs = WorkoutLog::where('user_id', $userId)
        ->whereBetween('log_date', [$mondayThisWeek, $sundayThisWeek])->get();
    $lastWeekLogs = WorkoutLog::where('user_id', $userId)
        ->whereBetween('log_date', [$mondayLastWeek, $sundayLastWeek])->get();

    $thisWeekVol = calculateLogsVolume($thisWeekLogs);
    $lastWeekVol = calculateLogsVolume($lastWeekLogs);
    $weeklyVolDiff = $thisWeekVol - $lastWeekVol;
    $weeklyVolPercent = computePercentDiff($thisWeekVol, $lastWeekVol);

    $thisWeekSets = $thisWeekLogs->sum('sets');
    $lastWeekSets = $lastWeekLogs->sum('sets');
    
    $thisWeekActiveDays = $thisWeekLogs->pluck('log_date')->unique()->count();
    $lastWeekActiveDays = $lastWeekLogs->pluck('log_date')->unique()->count();

    // C. BULAN INI vs BULAN KEMARIN
    $firstDayThisMonth = date('Y-m-01');
    $lastDayThisMonth = date('Y-m-t');

    $firstDayLastMonth = date('Y-m-01', strtotime('first day of last month'));
    $lastDayLastMonth = date('Y-m-t', strtotime('last day of last month'));

    $thisMonthLogs = WorkoutLog::where('user_id', $userId)
        ->whereBetween('log_date', [$firstDayThisMonth, $lastDayThisMonth])->get();
    $lastMonthLogs = WorkoutLog::where('user_id', $userId)
        ->whereBetween('log_date', [$firstDayLastMonth, $lastDayLastMonth])->get();

    $thisMonthVol = calculateLogsVolume($thisMonthLogs);
    $lastMonthVol = calculateLogsVolume($lastMonthLogs);
    $monthlyVolDiff = $thisMonthVol - $lastMonthVol;
    $monthlyVolPercent = computePercentDiff($thisMonthVol, $lastMonthVol);

    $thisMonthSets = $thisMonthLogs->sum('sets');
    $lastMonthSets = $lastMonthLogs->sum('sets');

    $thisMonthActiveDays = $thisMonthLogs->pluck('log_date')->unique()->count();
    $lastMonthActiveDays = $lastMonthLogs->pluck('log_date')->unique()->count();

    $thisMonthLabel = getIndonesianMonthLabel(date('Y-m'));
    $lastMonthLabel = getIndonesianMonthLabel(date('Y-m', strtotime('first day of last month')));

    // D. CUSTOM TANGGAL A VS TANGGAL B
    $customDateA = $request->query('date_a', date('Y-m-d'));
    $customDateB = $request->query('date_b', date('Y-m-d', strtotime('-1 day')));

    $customLogsA = WorkoutLog::where('user_id', $userId)->where('log_date', $customDateA)->get();
    $customLogsB = WorkoutLog::where('user_id', $userId)->where('log_date', $customDateB)->get();

    $customVolA = calculateLogsVolume($customLogsA);
    $customVolB = calculateLogsVolume($customLogsB);
    $customVolDiff = $customVolA - $customVolB;
    $customVolPercent = computePercentDiff($customVolA, $customVolB);

    $customSetsA = $customLogsA->sum('sets');
    $customSetsB = $customLogsB->sum('sets');

    $customExercisesA = $customLogsA->count();
    $customExercisesB = $customLogsB->count();

    return view('Dashboard.comparison', compact(
        'todayName', 'todayRoutineTitle', 'activeMode',
        'todayVol', 'yesterdayVol', 'dailyVolDiff', 'dailyVolPercent', 'todaySets', 'yesterdaySets', 'todayExercises', 'yesterdayExercises',
        'thisWeekVol', 'lastWeekVol', 'weeklyVolDiff', 'weeklyVolPercent', 'thisWeekSets', 'lastWeekSets', 'thisWeekActiveDays', 'lastWeekActiveDays',
        'thisMonthVol', 'lastMonthVol', 'monthlyVolDiff', 'monthlyVolPercent', 'thisMonthSets', 'lastMonthSets', 'thisMonthActiveDays', 'lastMonthActiveDays',
        'thisMonthLabel', 'lastMonthLabel',
        'customDateA', 'customDateB', 'customVolA', 'customVolB', 'customVolDiff', 'customVolPercent', 'customSetsA', 'customSetsB', 'customExercisesA', 'customExercisesB'
    ));
});

// 6. Settings Page Route (/dashboard/settings)
Route::get('/dashboard/settings', function () {
    if (!Auth::check() && !session('user')) {
        return redirect('/login')->with('error', 'AKSES DITOLAK! SILAKAN LOGIN ATAU DAFTAR AKUN.');
    }

    $userId = getAuthUserId();
    $user = User::find($userId);

    $todayInfo = getTodayInfo($userId);
    $todayName = $todayInfo['todayName'];
    $todayRoutineTitle = $todayInfo['todayRoutineTitle'];

    return view('Dashboard.settings', compact('user', 'todayName', 'todayRoutineTitle'));
});

// Update Profile
Route::post('/dashboard/settings/profile', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $user = User::find($userId);
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $userId,
    ], [
        'name.required' => 'NAMA LENGKAP WAJIB DIISI.',
        'email.required' => 'ALAMAT EMAIL WAJIB DIISI.',
        'email.unique' => 'EMAIL SUDAH DIGUNAKAN OLEH AKUN LAIN.',
    ]);

    $user->name = $request->name;
    $user->email = strtolower($request->email);
    $user->save();

    $userName = strtoupper($user->name);
    session(['user' => $userName, 'user_email' => $user->email]);

    return redirect('/dashboard/settings')->with('success', 'PROFIL PENGGUNA BERHASIL DIPERBARUI!');
});

// Update Password
Route::post('/dashboard/settings/password', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $user = User::find($userId);
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:6|confirmed',
    ], [
        'current_password.required' => 'KATA SANDI LAMA WAJIB DIISI.',
        'new_password.required' => 'KATA SANDI BARU WAJIB DIISI.',
        'new_password.min' => 'KATA SANDI BARU MINIMAL 6 KARAKTER.',
        'new_password.confirmed' => 'KONFIRMASI KATA SANDI BARU TIDAK COCOK.',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'KATA SANDI LAMA SALAH! PERUBAHAN DITOLAK.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect('/dashboard/settings')->with('success', 'KATA SANDI AKUN BERHASIL DIPERBARUI!');
});

// Save Workout Log Entry (With Duration Seconds Support)
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
        'duration_seconds' => 'nullable|integer|min:0',
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
        'duration_seconds' => $request->duration_seconds ?? null,
    ]);

    $redirectView = $request->input('view', 'daily');
    return redirect('/dashboard/logs?view=' . $redirectView . '&date=' . $request->log_date)->with('success', 'CATATAN LATIHAN ' . strtoupper($request->exercise_name) . ' BERHASIL DISIMPAN!');
});

// Delete Workout Log Entry
Route::post('/dashboard/logs/delete', function (Request $request) {
    $userId = getAuthUserId();
    if (!$userId) return redirect('/login')->with('error', 'AKSES DITOLAK!');

    $log = WorkoutLog::where('user_id', $userId)->where('id', $request->log_id)->first();
    if ($log) {
        $date = $log->log_date;
        $view = $request->input('view', 'daily');
        $log->delete();
        return redirect('/dashboard/logs?view=' . $view . '&date=' . $date)->with('info', 'CATATAN LATIHAN BERHASIL DIHAPUS.');
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
