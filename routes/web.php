<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DhenyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Participant\ParticipantControllerUser;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Participant\QuizController as ParticipantQuizController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\AssignmentController as InstructorAssignmentController;
use App\Http\Controllers\Participant\AssignmentController as ParticipantAssignmentController;
use App\Http\Controllers\Participant\MeetingController as ParticipantMeetingController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dheny', [DhenyController::class, 'index'])->name("view.dheny");
Route::get('/contact', [ContactController::class, 'index'])->name("contact");

Route::get('/course', [CourseController::class, 'index'])->name('course.index');
Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
Route::post('/course', [CourseController::class, 'store'])->name('course.store');

Route::middleware(['auth', 'role:participant'])->group(function () {
    Route::get('/course/{course}/read/{topic}', [CourseController::class, 'read'])->name('course.read');
    Route::post('/course/{course}/{topic}/read/done', [CourseController::class, 'completed'])->name('course.done');

    Route::post('/course/{course}/{topic}/read/quiz', [CourseController::class, 'submit'])->name('course.submit');
    Route::delete('/course/{course}/{topic}/read/quiz', [CourseController::class, 'destroy'])->name('course.destroy');

    Route::post('/course/{course}/{topic}/read/assignment', [CourseController::class, 'assignment'])->name('course.assignment');
    Route::delete('/course/{course}/{topic}/read/assignment', [CourseController::class, 'destroyAssignment'])->name('course.destroyAssignment');

    Route::post('/update-exitcount', [CourseController::class, 'updateExitCount'])->name('course.exitcount');
    Route::post('/update-answer', [CourseController::class, 'updateAnswer'])->name('course.answer');
});

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::name('participant.')->prefix('/participant')->middleware(['role:participant'])->group(function () {
        // Route::get('/my', [DashboardController::class, 'participant'])->name('index');
        Route::get('/quiz', [ParticipantQuizController::class, 'index'])->name('quiz.index');
        Route::get('/quiz/{quiz}/result', [ParticipantQuizController::class, 'result'])->name('quiz.result');

        Route::get('/assignment', [ParticipantAssignmentController::class, 'index'])->name('assignment.index');
        Route::get('/assignment/{result}/result', [ParticipantAssignmentController::class, 'result'])->name('assignment.result');
        Route::get('/meeting', [ParticipantMeetingController::class, 'index'])->name('meeting.index');
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
        Route::resource('/meeting', MeetingController::class)->except('show');
        Route::get('/enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
        Route::put('/enrollment/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollment.update');
        Route::put('/enrollment/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollment.update');
        Route::post('/enrollment', [EnrollmentController::class, 'updateAll'])->name('enrollment.updateAll');
        Route::delete('/enrollment/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');

        Route::resource('/question', QuestionController::class);
        Route::get('/question/{quiz}/create', [QuestionController::class, 'createWithQuiz'])->name('question.createWithQuiz');
    });

    Route::name('instructor.')->prefix('/instructor')->middleware(['role:instructor'])->group(function () {
        Route::get('/course', [InstructorCourseController::class, 'index'])->name('course.index');
        Route::get('/quiz/result', [InstructorQuizController::class, 'index'])->name('quiz.result');
        Route::get('/quiz/{attempt}/show', [InstructorQuizController::class, 'show'])->name('quiz.show');
        Route::delete('/quiz/{attempt}', [InstructorQuizController::class, 'destroy'])->name('quiz.destroy');
        Route::get('/quiz/feedback/{quiz}/{result}', [InstructorQuizController::class, 'feedback'])->name('quiz.feedback');
        Route::post('/quiz/feedback/{quiz}/{result}', [InstructorQuizController::class, 'storeFeedback'])->name('quiz.storeFeedback');
        Route::get('/quiz/feedback/{quiz}/{result}/edit', [InstructorQuizController::class, 'editFeedback'])->name('quiz.editFeedback');
        Route::post('/quiz/feedback/{quiz}/{result}/edit', [InstructorQuizController::class, 'updateFeedback'])->name('quiz.updateFeedback');
        Route::get('/quiz/feedback/{quiz}/{result}/delete', [InstructorQuizController::class, 'deleteFeedback'])->name('quiz.deleteFeedback');

        Route::get('/assignment', [InstructorAssignmentController::class, 'index'])->name('assignment.index');
        Route::get('/assignment/{result}/show', [InstructorAssignmentController::class, 'show'])->name('assignment.show');
        Route::get('/assignment/{result}/create', [InstructorAssignmentController::class, 'create'])->name('assignment.create');
        Route::post('/assignment/{result}', [InstructorAssignmentController::class, 'store'])->name('assignment.store');
        Route::get('/assignment/{result}/edit', [InstructorAssignmentController::class, 'edit'])->name('assignment.edit');
        Route::put('/assignment/{result}', [InstructorAssignmentController::class, 'update'])->name('assignment.update');
        Route::delete('/assignment/{result}', [InstructorAssignmentController::class, 'destroy'])->name('assignment.destroy');
    });
});

require __DIR__ . '/auth.php';
