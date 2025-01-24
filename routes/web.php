<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QrAbsenController;

Route::get('/', function () {
    return view('pages.auth.auth-login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [UserController::class, 'dashboard'])->name('home');

    Route::get('/home', [AttendanceController::class, 'dashboard'])->name('home');

    Route::resource('users', UserController::class);
    Route::resource('companies', CompanyController::class);
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::resource('permissions', PermissionController::class);
    Route::resource('qr_absens', QrAbsenController::class);


    Route::get('/qr-absens/{id}/download', [QrAbsenController::class, 'downloadPDF'])->name('qr_absens.download');
    Route::get('/attendances/download-pdf', [AttendanceController::class, 'downloadPDF'])->name('attendances.downloadPDF');
    Route::get('/permission/download-pdf', [PermissionController::class, 'downloadPDF'])->name('permission.downloadPDF');
});
