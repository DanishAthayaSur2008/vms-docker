<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;


// ===================== USER =====================

Route::middleware('maintenance')->group(function () {

    Route::view('/', 'index')->name('home');

    // Halaman pilihan: Register atau Login
    Route::get('/checkin', [VisitorController::class, 'checkin'])
        ->name('checkin');

    // REGISTER (form lengkap, untuk visitor baru)
    Route::get('/checkin/register', [VisitorController::class, 'checkinRegister'])
        ->name('checkin.register');

    Route::post('/checkin/register', [VisitorController::class, 'store'])
        ->name('visitor.store');

    // LOGIN (form singkat, untuk visitor yang sudah pernah check-in)
    Route::get('/checkin/login', [VisitorController::class, 'checkinLogin'])
        ->name('checkin.login');

    Route::post('/checkin/login', [VisitorController::class, 'loginStore'])
        ->name('visitor.login.store');

    Route::get('/checkin/lookup', [VisitorController::class, 'lookup'])
        ->name('checkin.lookup');

    Route::get('/checkout', [VisitorController::class, 'checkout'])
        ->name('checkout');

    Route::post('/checkout/search', [VisitorController::class, 'searchVisitor'])
        ->name('checkout.search');

    Route::post('/checkout', [VisitorController::class, 'processCheckout'])
        ->name('visitor.checkout');

    Route::view('/success', 'success')->name('success');

    Route::get('/checkin/lookup', [VisitorController::class, 'lookup'])
    ->middleware('throttle:15,1')
    ->name('checkin.lookup');

    Route::post('/checkin/login', [VisitorController::class, 'loginStore'])
    ->middleware('throttle:15,1')
    ->name('visitor.login.store');

});



// ===================== ADMIN =====================


// LOGIN

Route::middleware('guest')->group(function () {

    Route::get('/admin/login',
        [AuthController::class, 'login']
    )->name('login');


    Route::post('/admin/login',
        [AuthController::class, 'authenticate']
    )->name('process');

});


Route::middleware('auth')->group(function () {

    // LOGOUT

    Route::get('/admin/logout',
        [AuthController::class, 'logout']
    )->name('logout');



    // DASHBOARD

    Route::get('/admin/dashboard',
        [AdminController::class, 'dashboard']
    )->name('admin.dashboard');

    // Dashboard (JSON, dipakai untuk polling/auto-update statistik)
    Route::get('/admin/dashboard/data',
        [AdminController::class, 'dashboardData']
    )->name('admin.dashboard.data');



    // DATA VISITOR

    // Daftar Visitor
    Route::get('/admin/visitor',
        [AdminController::class, 'visitor']
    )->name('admin.visitor');

    // Data Visitor (JSON, dipakai untuk polling/auto-update tabel)
    Route::get('/admin/visitor/data',
        [AdminController::class, 'visitorData']
    )->name('admin.visitor.data');

    // Form Edit Visitor
    Route::get('/admin/visitor/{visitor}/edit',
        [AdminController::class, 'edit']
    )->name('admin.visitor.edit');

    // Update Visitor
    Route::put('/admin/visitor/{visitor}',
        [AdminController::class, 'update']
    )->name('admin.visitor.update');

    Route::patch('/admin/visitor/{visitor}/checkout',
    [AdminController::class, 'checkout']
)->name('admin.visitor.checkout');

    // Hapus Visitor
    Route::delete('/admin/visitor/{visitor}',
        [AdminController::class, 'destroy']
    )->name('admin.visitor.destroy');

    // Blacklist Visitor
    Route::post('/admin/visitor/{visitor}/blacklist',
        [AdminController::class, 'blacklistStore']
    )->name('admin.visitor.blacklist');

    // Unblacklist Visitor
    Route::post('/admin/visitor/{visitor}/unblacklist',
        [AdminController::class, 'blacklistRemove']
    )->name('admin.visitor.unblacklist');

    // Detail Visitor (HARUS PALING BAWAH)
    Route::get('/admin/visitor/{visitor}',
        [AdminController::class, 'visitorDetail']
    )->name('admin.visitor.detail');


    // DETAIL VISITOR

    Route::get('/admin/visitor-active',
        [AdminController::class, 'visitorActive']
    )->name('admin.visitor.active');


    // BLACKLIST (HALAMAN KELOLA)

    Route::get('/admin/blacklist',
        [AdminController::class, 'blacklistIndex']
    )->name('admin.blacklist');

    Route::delete('/admin/blacklist/{blacklist}',
        [AdminController::class, 'blacklistDestroy']
    )->name('admin.blacklist.destroy');


    // LAPORAN

    Route::get('/admin/laporan',
        [AdminController::class, 'laporan']
    )->name('admin.laporan');

    Route::get('/admin/laporan/export',
        [AdminController::class, 'laporanExport']
    )->name('admin.laporan.export');


    // PROFILE

    Route::get('/admin/profile',
        [AdminController::class, 'profile']
    )->name('admin.profile');

    Route::post('/admin/profile',
        [AdminController::class, 'profileUpdate']
    )->name('admin.profile.update');

    Route::post('/admin/profile/password',
        [AdminController::class, 'profilePassword']
    )->name('admin.profile.password');


    // SETTING

    Route::get('/admin/setting',
        [AdminController::class, 'setting']
    )->name('admin.setting');

    Route::post('/admin/setting',
        [AdminController::class, 'settingUpdate']
    )->name('admin.setting.update');

});