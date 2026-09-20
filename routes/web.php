<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\DailyLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\PregnancyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectionController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\EnsureUnlocked;
use App\Http\Middleware\EnsureWife;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/privasi', [PageController::class, 'privacy'])->name('privacy');
Route::get('/syarat', [PageController::class, 'terms'])->name('terms');

Route::middleware('guest')->group(function () {
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');
    Route::get('/pin', [PinController::class, 'show'])->name('pin.show');
    Route::post('/pin', [PinController::class, 'unlock'])->name('pin.unlock');

    Route::middleware(EnsureUnlocked::class)->group(function () {
        Route::get('/onboarding', [ProfileController::class, 'showOnboarding'])->name('onboarding.show');
        Route::post('/onboarding', [ProfileController::class, 'storeOnboarding'])->name('onboarding.store');

        Route::get('/dasbor', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar');
        Route::get('/catatan', [DailyLogController::class, 'index'])->name('logs.index');
        Route::get('/siklus', [CycleController::class, 'index'])->name('cycles.index');
        Route::get('/analitik', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/proyeksi', [ProjectionController::class, 'index'])->name('projection');
        Route::get('/laporan', [ReportController::class, 'index'])->name('report');
        Route::get('/laporan/pdf', [ReportController::class, 'pdf'])->name('report.pdf');

        Route::get('/kehamilan', [PregnancyController::class, 'index'])->name('pregnancy.index');

        Route::get('/push/vapid', [PushController::class, 'vapidKey'])->name('push.vapid');
        Route::post('/push/subscribe', [PushController::class, 'subscribe'])->name('push.subscribe');
        Route::get('/notifikasi', [PushController::class, 'settings'])->name('push.settings');

        Route::get('/pengaturan', [SettingsController::class, 'index'])->name('settings');
        Route::post('/pengaturan', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/pengaturan/export', [SettingsController::class, 'export'])->name('settings.export');
        Route::post('/pengaturan/hapus', [SettingsController::class, 'destroy'])->name('settings.destroy');
        Route::post('/pengaturan/pin', [PinController::class, 'update'])->name('settings.pin');
        Route::post('/pengaturan/kunci', [PinController::class, 'lock'])->name('settings.lock');

        Route::middleware(EnsureWife::class)->group(function () {
            Route::post('/siklus', [CycleController::class, 'store'])->name('cycles.store');
            Route::post('/siklus/hari-ini', [CycleController::class, 'quickToday'])->name('cycles.today');
            Route::delete('/siklus/{cycle}', [CycleController::class, 'destroy'])->name('cycles.destroy');
            Route::post('/catatan', [DailyLogController::class, 'store'])->name('logs.store');
            Route::post('/pil', [DailyLogController::class, 'togglePill'])->name('pill.toggle');

            Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');

            Route::post('/kehamilan/aktif', [PregnancyController::class, 'activate'])->name('pregnancy.activate');
            Route::post('/kehamilan/selesai', [PregnancyController::class, 'deactivate'])->name('pregnancy.deactivate');

            Route::get('/pasangan', [PartnerController::class, 'index'])->name('partner');
            Route::post('/pasangan/kode', [PartnerController::class, 'createInvite'])->name('partner.invite');
            Route::post('/pasangan/berbagi', [PartnerController::class, 'toggleShare'])->name('partner.share');
            Route::post('/pasangan/cabut', [PartnerController::class, 'revoke'])->name('partner.revoke');
        });
    });
});
