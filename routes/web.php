<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DisplayController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin) return redirect('/admin/dashboard');
        return redirect('/staff');
    }
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/manual-queue', [AdminController::class, 'manualQueue'])->name('admin.manual-queue');
    
    Route::get('/admin/queue', [AdminController::class, 'queue'])->name('admin.queue');
    Route::get('/admin/history', [AdminController::class, 'history'])->name('admin.history');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    
    Route::get('/admin/accounts', [AdminController::class, 'accounts'])->name('admin.accounts');
    Route::post('/admin/accounts', [AdminController::class, 'storeAccount'])->name('admin.accounts.store');
    Route::delete('/admin/accounts/{id}', [AdminController::class, 'deleteAccount'])->name('admin.accounts.delete');
    
    Route::get('/admin/windows', [AdminController::class, 'windows'])->name('admin.windows');
    Route::post('/admin/windows', [AdminController::class, 'storeWindow'])->name('admin.windows.store');
    Route::delete('/admin/windows/{id}', [AdminController::class, 'deleteWindow'])->name('admin.windows.delete');
    
    Route::post('/admin/courses', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
    Route::delete('/admin/courses/{id}', [AdminController::class, 'deleteCourse'])->name('admin.courses.delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/staff', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::get('/staff/data', [StaffController::class, 'getData'])->name('staff.data');
    Route::post('/staff/call-next', [StaffController::class, 'callNext'])->name('staff.call-next');
    Route::post('/staff/complete', [StaffController::class, 'complete'])->name('staff.complete');
    Route::post('/staff/skip', [StaffController::class, 'skip'])->name('staff.skip');
});

Route::get('/kiosk', function () { return view('kiosk.index'); })->name('kiosk.index');
Route::get('/kiosk/student-info', [KioskController::class, 'createStudentInfo'])->name('kiosk.student-info');
Route::post('/kiosk/student-info', [KioskController::class, 'storeStudentInfo'])->name('kiosk.student-info.store');
Route::get('/kiosk/transaction', function () { return view('kiosk.transaction'); })->name('kiosk.transaction');
Route::post('/kiosk/transaction', [KioskController::class, 'storeTransaction'])->name('kiosk.transaction.store');
Route::get('/kiosk/queue-number', [KioskController::class, 'showQueueNumber'])->name('kiosk.queue-number');

Route::get('/display', [DisplayController::class, 'index'])->name('display');
Route::get('/display/data', [DisplayController::class, 'getData'])->name('display.data');

require __DIR__.'/auth.php';