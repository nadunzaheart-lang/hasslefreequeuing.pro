<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DisplayController;

Route::get('/', function () {
    if (auth()->check()) {
        // If Admin, go to Admin Dashboard. If Staff, go to Staff.
        if (auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }
        return redirect('/staff');
    }
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/history', [AdminController::class, 'history'])->name('admin.history');
    Route::get('/admin/accounts', [AdminController::class, 'accounts'])->name('admin.accounts');
    Route::post('/admin/accounts', [AdminController::class, 'storeAccount'])->name('admin.accounts.store');
    Route::delete('/admin/accounts/{id}', [AdminController::class, 'deleteAccount'])->name('admin.accounts.delete');
    Route::get('/admin/windows', [AdminController::class, 'windows'])->name('admin.windows');
    Route::post('/admin/courses', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
    Route::delete('/admin/courses/{id}', [AdminController::class, 'deleteCourse'])->name('admin.courses.delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/staff', [StaffController::class, 'dashboard'])->name('staff.dashboard');
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

require __DIR__.'/auth.php';