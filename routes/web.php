<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserContentController;
use App\Http\Controllers\ScannerController;

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('landingpage');

});

/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {

    return view('register');

});

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

Route::get('/register/success', function () {

    return view('register-success');

})->name('register.success');

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/

Route::get('/api/provinsi', [ApiController::class, 'getProvinsi']);

Route::get('/api/kota/{provinsi}', [ApiController::class, 'getKota']);

Route::get('/api/sekolah/{kota}', [ApiController::class, 'getSekolah']);

Route::get('/api/prodi', [ApiController::class, 'getProgramStudi']);

Route::get('/api/kegiatan', [ApiController::class, 'getKegiatanLimit']);

/*
|--------------------------------------------------------------------------
| USER LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {

    return view('login');

})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.action');

/*
|--------------------------------------------------------------------------
| USER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    if (!session('loggedin')) {

        return redirect('/login');

    }

    return view('user.dashboard');

})->name('dashboard');

/*
|--------------------------------------------------------------------------
| USER LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    session()->flush();

    return redirect('/login');

})->name('logout');

/*
|--------------------------------------------------------------------------
| USER CONTENT AJAX
|--------------------------------------------------------------------------
*/

Route::get('/user/content/{type}', [UserContentController::class, 'load'])
    ->name('user.content');

/*
|--------------------------------------------------------------------------
| USER SCANNER
|--------------------------------------------------------------------------
*/

Route::get('/scanner', [ScannerController::class, 'index'])
    ->name('scanner');

Route::post('/process-qr', [ScannerController::class, 'processQr'])
    ->name('process.qr');

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.action');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/admin', [AdminController::class, 'index'])
    ->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| STAFF SCANNER
|--------------------------------------------------------------------------
*/

Route::get('/admin/staff/scanner', [AdminController::class, 'staffScanner'])
    ->name('admin.staff.scanner');

/*
|--------------------------------------------------------------------------
| STAFF SCANNER CONTENT & PRESENSI
|--------------------------------------------------------------------------
*/

Route::get('/admin/staff/content/{iduser}', [AdminController::class, 'staffContent'])
    ->name('admin.staff.content');

Route::post('/admin/staff/presensi/update', [AdminController::class, 'updatePresensi'])
    ->name('admin.staff.presensi.update');

/*
|--------------------------------------------------------------------------
| STAFF DASHBOARD API
|--------------------------------------------------------------------------
*/

Route::get('/admin/staff/api/summary', [AdminController::class, 'getStaffSummary'])
    ->name('admin.staff.api.summary');

Route::get('/admin/staff/api/kegiatan', [AdminController::class, 'getKegiatanBySesi'])
    ->name('admin.staff.api.kegiatan');

Route::get('/admin/staff/api/summary-sesi', [AdminController::class, 'getSummarySesi'])
    ->name('admin.staff.api.summary.sesi');

Route::get('/admin/staff/api/summary-kegiatan', [AdminController::class, 'getSummaryKegiatan'])
    ->name('admin.staff.api.summary.kegiatan');

Route::get('/admin/staff/api/hadir-oc-sesi', [AdminController::class, 'getHadirOcSesi'])
    ->name('admin.staff.api.hadir.oc.sesi');

Route::get('/admin/staff/api/hadir-oc-kegiatan', [AdminController::class, 'getHadirOcKegiatan'])
    ->name('admin.staff.api.hadir.oc.kegiatan');

/*
|--------------------------------------------------------------------------
| SUPER ADMIN CONTENT
|--------------------------------------------------------------------------
*/

Route::match(['GET', 'POST'], '/admin/super/content', [AdminController::class, 'superContent'])
    ->name('admin.super.content');

Route::post(
    '/admin/super/booth/action',
    [AdminController::class, 'boothAction']
);

Route::post(
    '/admin/super/staff/action',
    [AdminController::class, 'staffAction']
);

Route::post(
    '/admin/super/reward-config/update',
    [AdminController::class, 'rewardConfigUpdate']
);

Route::get(
    '/admin/super/booth/{idbooth}/qr',
    [AdminController::class, 'generateQrBooth']
)->name('admin.super.booth.qr');

Route::get(
    '/admin/super/peserta/export',
    [AdminController::class, 'exportPeserta']
)->name('admin.super.peserta.export');

Route::post('/reward/update', [AdminController::class, 'rewardConfigUpdate'])
    ->name('reward.update');

Route::get('/user/reward/claim', [UserContentController::class, 'generateClaimQR'])
    ->name('user.reward.claim');

Route::get('/user/reward/claim', [UserContentController::class, 'generateClaimQR']);

Route::get(
    '/admin/staff/reward/{iduser}',
    [ScannerController::class, 'rewardContent']
);

Route::post(
    '/admin/staff/reward/confirm',
    [ScannerController::class, 'confirmReward']
)->name('admin.staff.reward.confirm');