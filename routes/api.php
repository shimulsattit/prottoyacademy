<?php

use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Web\StudentExamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Content Routes
Route::get('/categories', [ContentController::class, 'categories']);
Route::get('/categories/{id}/sub', [ContentController::class, 'subCategories']);
Route::get('/job-category/{slug}', [ContentController::class, 'jobCategoryDetails']);
Route::get('/questions', [ContentController::class, 'questions']);

Route::post('/login', [StudentAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [StudentAuthController::class, 'me']);
    Route::post('/logout', [StudentAuthController::class, 'logout']);

    // Exam Routes
    Route::prefix('exam')->group(function () {
        Route::post('/start/{exam}', [StudentExamController::class, 'start']);
        Route::post('/submit/{exam}', [StudentExamController::class, 'submit']);
    });
});
