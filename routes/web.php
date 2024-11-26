<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DhenyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ParticipantController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dheny', [DhenyController::class, 'index'])->name("view.dheny");

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::name('admin.')->middleware(['role:author'])->group(function () {
        Route::resource('/participant', ParticipantController::class)->except('show');
    });
});

require __DIR__ . '/auth.php';
