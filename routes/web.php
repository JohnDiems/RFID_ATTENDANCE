<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\LunchController;
use App\Http\Controllers\Web\CardController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ScheduleController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ReportController;
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
Route::get('/', [AttendancesController::class, 'home'])->name('home');

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

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'editOwn'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateOwn'])->name('profile.update-own');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings/update', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard']);

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Profile Management
    Route::get('/profiles', [ProfileController::class, 'index'])->name('admin.profiles');
    Route::get('/profiles/create', [ProfileController::class, 'create'])->name('admin.profiles.create');
    Route::post('/profiles', [ProfileController::class, 'store'])->name('admin.profiles.store');
    Route::get('/profiles/{profile}', [ProfileController::class, 'show'])->name('admin.profiles.show');
    Route::get('/profiles/{profile}/edit', [ProfileController::class, 'edit'])->name('admin.profiles.edit');
    Route::put('/profiles/{profile}', [ProfileController::class, 'update'])->name('admin.profiles.update');
    Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy'])->name('admin.profiles.destroy');
    Route::post('/profiles/{profile}/status', [ProfileController::class, 'updateStatus'])->name('admin.profiles.status');
    Route::get('/profiles/import', [ProfileController::class, 'import'])->name('admin.profiles.import');
    Route::post('/profiles/import', [ProfileController::class, 'processImport'])->name('admin.profiles.import.process');

    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('admin.attendance.report');

    // Lunch Management
    Route::get('/lunch', [LunchController::class, 'index'])->name('admin.lunch.index');
    Route::get('/lunch/report', [LunchController::class, 'report'])->name('admin.lunch.report');

    // Card Management
    Route::get('/cards', [CardController::class, 'index'])->name('admin.cards.index');
    Route::get('/cards/create', [CardController::class, 'create'])->name('admin.cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('admin.cards.store');
    Route::get('/cards/{card}', [CardController::class, 'show'])->name('admin.cards.show');
    Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('admin.cards.edit');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('admin.cards.update');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('admin.cards.destroy');
    Route::get('/cards/report', [CardController::class, 'report'])->name('admin.cards.report');

    // Schedule Management
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('admin.schedules');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('admin.schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('admin.schedules.show');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('admin.schedules.edit');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
    Route::post('/schedules/{schedule}/toggle', [ScheduleController::class, 'toggleStatus'])->name('admin.schedules.toggle');
    Route::get('/schedules/report', [ScheduleController::class, 'report'])->name('admin.schedules.report');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('admin.reports.attendance');
    Route::get('/reports/lunch', [ReportController::class, 'lunch'])->name('admin.reports.lunch');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('admin.reports.export');
});

// Teacher Routes
Route::prefix('teacher')->middleware(['auth', 'role:teacher'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'teacherDashboard'])->name('teacher.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'teacherDashboard']);

    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'teacherIndex'])->name('teacher.attendance');
    Route::get('/attendance/report', [AttendanceController::class, 'teacherReport'])->name('teacher.attendance.report');

    // Student Management
    Route::get('/students', [ProfileController::class, 'teacherIndex'])->name('teacher.students');
    Route::get('/students/{profile}', [ProfileController::class, 'teacherShow'])->name('teacher.students.show');

    // Reports
    Route::get('/reports', [ReportController::class, 'teacherIndex'])->name('teacher.reports');
});

// Student Routes
Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    Route::get('/attendance', [DashboardController::class, 'studentDashboard'])->name('student.attendance');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'studentShow'])->name('student.profile');
    Route::get('/profile/edit', [ProfileController::class, 'studentEdit'])->name('student.profile.edit');
    Route::put('/profile', [ProfileController::class, 'studentUpdate'])->name('student.profile.update');

    // Attendance History
    Route::get('/history', [AttendanceController::class, 'studentHistory'])->name('student.history');
    Route::get('/history/attendance', [AttendanceController::class, 'studentAttendanceHistory'])->name('student.history.attendance');
    Route::get('/history/lunch', [LunchController::class, 'studentLunchHistory'])->name('student.history.lunch');
});
