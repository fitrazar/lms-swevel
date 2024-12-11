<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DhenyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Participant\ParticipantControllerUser;
use App\Http\Controllers\Participant\QuizController as ParticipantQuizController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dheny', [DhenyController::class, 'index'])->name("view.dheny");
Route::get('/contact', [ContactController::class, 'index'])->name("contact");

Route::get('/course', [CourseController::class, 'index'])->name('course.index');
Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
Route::post('/course', [CourseController::class, 'store'])->name('course.store');
Route::get('/course/{course}/read/{topic}', [CourseController::class, 'read'])->name('course.read');
Route::post('/course/{course}/{topic}/read/done', [CourseController::class, 'completed'])->name('course.done');
Route::post('/course/{course}/{topic}/read/quiz', [CourseController::class, 'submit'])->name('course.submit');
Route::delete('/course/{course}/{topic}/read/quiz', [CourseController::class, 'destroy'])->name('course.destroy');
Route::post('/update-exitcount', [CourseController::class, 'updateExitCount'])->name('course.exitcount');
Route::post('/update-answer', [CourseController::class, 'updateAnswer'])->name('course.answer');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::name('participant.')->prefix('/participant')->middleware(['role:participant'])->group(function () {
        // Route::get('/my', [DashboardController::class, 'participant'])->name('index');
        Route::get('/quiz', [ParticipantQuizController::class, 'index'])->name('quiz.index');
        Route::get('/quiz/{quiz}/result', [ParticipantQuizController::class, 'result'])->name('quiz.result');
    });

    Route::middleware(['role:participant|instructor'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::name('admin.')->middleware(['role:author'])->group(function () {
        Route::resource('/participant', ParticipantController::class)->except('show');
        Route::resource('/instructor', controller: InstructorController::class)->except('show');
        Route::resource('/course', AdminCourseController::class)->except('show');
        // Route::resource('/course', AdminCourseController::class)->except('show');
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/setting', [SettingController::class, 'store'])->name('setting.store');
    });

    Route::middleware(['role:author|instructor'])->group(function () {
        Route::resource('/material', MaterialController::class)->except('show');
        Route::get('/material/{course}/create', [MaterialController::class, 'createWithCourse'])->name('course.createWithCourse');
        Route::resource('/assignment', AssignmentController::class)->except('show');
        Route::resource('/quiz', QuizController::class)->except('show');
        Route::get('/enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
        Route::put('/enrollment/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollment.update');
        Route::put('/enrollment/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollment.update');
        Route::post('/enrollment', [EnrollmentController::class, 'updateAll'])->name('enrollment.updateAll');
        Route::delete('/enrollment/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');

        Route::resource('/question', QuestionController::class);
        Route::get('/question/{quiz}/create', [QuestionController::class, 'createWithQuiz'])->name('question.createWithQuiz');
    });

    Route::name('instructor.')->middleware(['role:instructor'])->group(function () {
        Route::get('/instructor/course', [InstructorCourseController::class, 'index'])->name('course.index');
    });
});

require __DIR__ . '/auth.php';
