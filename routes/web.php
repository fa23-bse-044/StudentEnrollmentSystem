<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\AuthController;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('portal.dashboard');
    Route::post('/student/store', [PortalController::class, 'storeStudent'])->name('portal.student.store');
    Route::post('/student/save-marks/{id}', [PortalController::class, 'saveMarks'])->name('portal.student.save_marks');
    Route::get('/student/delete/{id}', [PortalController::class, 'destroyStudent'])->name('portal.student.destroy');

    Route::post('/profile/update-picture', [PortalController::class, 'updateProfilePicture'])->name('profile.update_picture');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::middleware(['auth'])->group(function () {
    // Previous standard dashboard pathways routes...

    // 4. Custom Quizzes Pathways System Grid
    Route::post('/quiz/save', [PortalController::class, 'saveQuizMark'])->name('portal.quiz.save');
    Route::get('/quiz/delete/{id}', [PortalController::class, 'deleteQuiz'])->name('portal.quiz.delete');

    // 5. Custom Assignments Pathways System Grid
    Route::post('/assignment/save', [PortalController::class, 'saveAssignment'])->name('portal.assignment.save');
    Route::get('/assignment/delete/{id}', [PortalController::class, 'deleteAssignment'])->name('portal.assignment.delete');
    Route::post('/assignment/submit/{id}', [PortalController::class, 'studentSubmitAssignment'])->name('portal.assignment.submit');
    Route::post('/assignment/grade/{id}', [PortalController::class, 'facultyGradeAssignment'])->name('portal.assignment.grade');
});
