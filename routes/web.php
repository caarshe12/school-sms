<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExamController; // Add this import
use App\Http\Controllers\ProfileController; // Keep this

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared Routes (Admin & Teacher)
    Route::middleware(['role:admin,teacher'])->group(function () {
        Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/report', [App\Http\Controllers\AttendanceController::class, 'report'])->name('attendance.report');
        Route::get('/attendance/create', [App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');

        Route::resource('exams', ExamController::class);
        Route::post('/exams/{exam}/marks', [ExamController::class, 'updateMark'])->name('exams.marks.update');
        
        // Read-only access to classes is needed for Exams
        Route::get('/classes', [SchoolClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{schoolClass}', [SchoolClassController::class, 'show'])
            ->name('classes.show')
            ->whereNumber('schoolClass'); // Prevent matching 'create'
    });

    // Student Only Routes
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/portal', [App\Http\Controllers\StudentPortalController::class, 'index'])->name('student.portal');
    });

    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::post('/students/{student}/exam-results', [StudentController::class, 'storeExamResult'])->name('students.exam-results.store');
        Route::resource('staff', StaffController::class);
        
        // Full access to classes (Create/Edit/Delete)
        Route::post('/classes', [SchoolClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/create', [SchoolClassController::class, 'create'])->name('classes.create');
        Route::get('/classes/{schoolClass}/edit', [SchoolClassController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{schoolClass}', [SchoolClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{schoolClass}', [SchoolClassController::class, 'destroy'])->name('classes.destroy');

        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
        Route::post('/exams/{exam}/marks', [ExamController::class, 'updateMark'])->name('exams.marks.update');
        Route::post('/exams/{exam}/marks/all', [ExamController::class, 'updateAllMarks'])->name('exams.marks.updateAll');
        Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');

        Route::resource('fees', FeeController::class);
        Route::post('/fees/{fee}/pay', [FeeController::class, 'pay'])->name('fees.pay');
        Route::post('/fees/{fee}/unpay', [FeeController::class, 'unpay'])->name('fees.unpay');
        Route::resource('payments', PaymentController::class);

        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';
