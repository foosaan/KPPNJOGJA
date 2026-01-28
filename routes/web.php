<?php

use Illuminate\Support\Facades\Route;

// Import Controllers from their new Namespaces
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Admin Namespace
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\KelolaLayananController;
use App\Http\Controllers\Admin\DivisiController;

// Staff Namespace
use App\Http\Controllers\Staff\StaffController;

// User Namespace
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\LayananGenerikController;


// ==================== HALAMAN AWAL ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== DASHBOARD REDIRECT (OTOMATIS BY ROLE) ====================
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'staff' => redirect()->route('staff.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => redirect('/'),
    };
})->name('dashboard');

// ==================== AUTENTIKASI ====================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

require __DIR__ . '/auth.php';

// ==================== ROUTE TERPROTEKSI (LOGIN) ====================
Route::middleware('auth')->group(function () {

    // ===== ADMIN / STAFF PROFILE =====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== USER PROFILE =====
    Route::middleware('role:user')->group(function () {
        Route::get('/user/profile', [ProfileController::class, 'editUserProfile'])->name('user.profile.edit');
        Route::patch('/user/profile', [ProfileController::class, 'updateUserProfile'])->name('user.profile.update');
    });

    // ===== STAFF PROFILE =====
    Route::middleware(['auth', 'role:staff'])->group(function () {
        Route::get('/staff/profile', [ProfileController::class, 'editStaffProfile'])->name('staff.profile.edit');
        Route::patch('/staff/profile', [ProfileController::class, 'updateStaffProfile'])->name('staff.profile.update');
        
        // Dashboard Staff Duplicate Route (Removed to avoid confusion, handled below)
    });

    // ==================== ADMIN MODULE ====================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // CRUD Users (Admin & Staff)
        Route::resource('/admins', AdminUserController::class);
        Route::resource('/staffs', AdminStaffController::class);

        // CRUD Layanan
        Route::resource('/layanan', KelolaLayananController::class);
        Route::post('/layanan/{layanan}/toggle', [KelolaLayananController::class, 'toggleStatus'])->name('layanan.toggle');

        // CRUD Divisi
        Route::post('/divisi', [DivisiController::class, 'store'])->name('divisi.store');
        Route::put('/divisi/{divisi}', [DivisiController::class, 'update'])->name('divisi.update');
        Route::delete('/divisi/{divisi}', [DivisiController::class, 'destroy'])->name('divisi.destroy');
        Route::post('/divisi/{divisi}/toggle', [DivisiController::class, 'toggle'])->name('divisi.toggle');

        // CRUD User (Management)
        Route::get('/users', [AdminUserController::class, 'indexUser'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'createUser'])->name('users.create');
        Route::post('/users/store', [AdminUserController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroyUser'])->name('users.destroy');
    });

    // ==================== STAFF MODULE ====================
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
        
        // Berkas Menu
        Route::get('/berkasmasuk', [StaffController::class, 'index'])->name('berkasmasuk');
        Route::get('/berkasproses', [StaffController::class, 'berkasProses'])->name('berkasproses');
        Route::get('/berkasselesai', [StaffController::class, 'berkasSelesai'])->name('berkasselesai');
        Route::get('/berkasditolak', [StaffController::class, 'berkasDitolak'])->name('berkasditolak');
        
        // Actions
        Route::put('/update-status/{id}/{type}', [StaffController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/feedback/{id}', [StaffController::class, 'updateFeedback'])->name('feedback.update');
    });

    // ==================== USER MODULE ====================
    Route::middleware('role:user')->prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

        // Dynamic Layanan Request
        Route::get('/layanan/{slug}', [LayananGenerikController::class, 'create'])->name('layanan.generik.create');
        Route::post('/layanan/{slug}', [LayananGenerikController::class, 'store'])->name('layanan.generik.store');
    });

    // ==================== LOGOUT ====================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});