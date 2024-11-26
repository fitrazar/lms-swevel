<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DhenyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dheny', [DhenyController::class, 'index'])->name("view.dheny");
Route::get('/contact', [ContactController::class, 'index'])->name("contact");
Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::middleware(['role:participant|instructor'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::name('admin.')->middleware(['role:author'])->group(function () {
        Route::resource('/participant', ParticipantController::class)->except('show');
        Route::resource('/instructor', controller: InstructorController::class)->except('show');
        Route::resource('/course', AdminCourseController::class)->except('show');
    });
});

require __DIR__ . '/auth.php';
