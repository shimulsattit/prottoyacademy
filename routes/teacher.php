<?php

use App\Http\Controllers\Teacher\TeacherAuthController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard',          [TeacherAuthController::class, 'dashboard'])->name('dashboard');
Route::get('questions',          [TeacherAuthController::class, 'questions'])->name('questions');
Route::get('questions/create',   [TeacherAuthController::class, 'createQuestion'])->name('questions.create');
Route::post('questions',         [TeacherAuthController::class, 'storeQuestion'])->name('questions.store');
Route::get('profile',            [TeacherAuthController::class, 'profile'])->name('profile');
Route::post('profile/update',    [TeacherAuthController::class, 'updateProfile'])->name('profile.update');
Route::post('logout',            [TeacherAuthController::class, 'logout'])->name('logout');
