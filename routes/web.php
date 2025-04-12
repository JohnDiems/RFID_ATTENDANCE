<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\LunchController;
use App\Http\Controllers\Web\CardController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ScheduleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AttendancesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider.
|
*/

// Public Routes
Route::get('/LandingPage.home', [AttendancesController::class, 'home'])->name('home');

// Landing Page - also accessible via /attendance for backward compatibility
Route::get('/attendance', [AttendancesController::class, 'home']);

// Mark Attendance Route
Route::post('/mark-attendance', [AttendancesController::class, 'markAttendance']);

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('admin.attendance.report');

    // Lunch Management
    Route::get('/lunch', [LunchController::class, 'index'])->name('admin.lunch.index');
    Route::get('/lunch/report', [LunchController::class, 'report'])->name('admin.lunch.report');

    // Card Management
    Route::resource('cards', CardController::class);
    Route::get('/cards/report', [CardController::class, 'report'])->name('admin.cards.report');

    // Profile Management
    Route::resource('profiles', ProfileController::class);
    Route::post('/profiles/{profile}/status', [ProfileController::class, 'updateStatus'])->name('admin.profiles.status');
    Route::get('/profiles/report', [ProfileController::class, 'report'])->name('admin.profiles.report');
    Route::get('/profiles/import', [ProfileController::class, 'import'])->name('admin.profiles.import');
    Route::post('/profiles/import', [ProfileController::class, 'processImport'])->name('admin.profiles.import.process');

    // Schedule Management
    Route::resource('schedules', ScheduleController::class);
    Route::post('/schedules/{schedule}/toggle', [ScheduleController::class, 'toggleStatus'])->name('admin.schedules.toggle');
    Route::get('/schedules/report', [ScheduleController::class, 'report'])->name('admin.schedules.report');
});

// Student Routes
Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('student.dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('student.profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('student.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('student.profile.update');

    // Attendance & Lunch Records
    Route::get('/attendance', [AttendanceController::class, 'show'])->name('student.attendance.show');
    Route::get('/lunch', [LunchController::class, 'show'])->name('student.lunch.show');
});
