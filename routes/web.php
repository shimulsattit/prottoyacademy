<?php

use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Portal\LoginController;
use App\Http\Controllers\Web\WebsiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Web\StudentExamController;

Route::get('/', [WebsiteController::class, 'index'])->name('home');
Route::get('/login', [WebsiteController::class, 'login'])->name('login');
Route::get('/blogs', [WebsiteController::class, 'blogs'])->name('blogs');
Route::get('/register', [WebsiteController::class, 'register'])->name('register');
Route::post('/login', [WebsiteController::class, 'postLogin'])->name('login.post');
Route::post('/register', [WebsiteController::class, 'postRegister'])->name('register.post');
Route::get('/forget-password', [WebsiteController::class, 'forgetPassword'])->name('forget.password');
Route::get('/verify-otp', [WebsiteController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/reset-password', [WebsiteController::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [WebsiteController::class, 'postResetPassword'])->name('reset.password.post');
Route::post('/verify-otp', [WebsiteController::class, 'postVerifyOtp'])->name('verify.otp.post');
Route::post('/forget-password', [WebsiteController::class, 'postForgetPassword'])->name('forget.password.post');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('contact');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('portal', function() {
    return redirect()->route('portal.login');
});

Route::get('portal/login', [LoginController::class, 'index'])->name('portal.login');
Route::post('portal/login/access', [LoginController::class, 'login'])->name('portal.login.post');

Route::get('get-exam-by-year', [ExamController::class, 'getExamByYear'])->name('get-exam-by-year');
Route::post('search/category', [SettingController::class, 'searchForCategory'])->name('search.job-category');
Route::post('search/all-category', [SettingController::class, 'searchForAllCategory'])->name('search.all-category');
Route::post('search/job-category', [SettingController::class, 'searchByJobCategory'])->name('search.job-category');
Route::post('search/passage', [SettingController::class, 'searchForPassage'])->name('search.passage');
Route::post('search/year', [SettingController::class, 'searchByYear'])->name('search.year');
Route::get('slug-check', [SettingController::class, 'slugCheck'])->name('slug.check');

Route::post('editor/upload', [SettingController::class, 'upload'])->name('editor.upload');

Route::middleware(['isStudent'])->group(function () {
    Route::post('student/logout', [LoginController::class, 'logout'])->name('student.logout');
    Route::get('student/dashboard', [LoginController::class, 'dashboard'])->name('student.dashboard');
    Route::get('student/profile', [LoginController::class, 'profile'])->name('student.profile');
    Route::get('student/password', [LoginController::class, 'password'])->name('student.password');
    Route::get('student/exams', [LoginController::class, 'myAttendedExams'])->name('student.exams');


    Route::post('student/profile/update', [LoginController::class, 'updateProfile'])->name('update.profile');
    Route::post('student/password/update', [LoginController::class, 'updatePassword'])->name('update.password');

    Route::prefix('exam')->group(function(){
        Route::post('/start/{exam}', [StudentExamController::class, 'start'])->name('exam.start');
        Route::post('/submit/{exam}', [StudentExamController::class, 'submit'])->name('exam.submit');
    });
});

Route::get('exam/{slug}', [StudentExamController::class, 'show'])->name('exam.show');

Route::get('sitemap.xml', [App\Http\Controllers\Web\SitemapController::class, 'index'])->name('sitemap');

Route::get('/clear-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    
    $out = "Cache cleared successfully!<br><br>";
    $slug = "পিএসসি-ও-অন্যান্য-পরীক্ষা";
    $cat = \App\Models\Category::where('slug', $slug)->first();
    if ($cat) {
        $out .= "Category 27 exists! Name: {$cat->name}, Slug: {$cat->slug}, Status: {$cat->status}<br>";
    } else {
        $out .= "Category 27 NOT found in DB by exact slug '{$slug}'!<br>";
        // Find by name
        $catName = \App\Models\Category::where('name', 'like', '%পিএসসি%')->first();
        if ($catName) {
            $out .= "Found category by Name LIKE '%পিএসসি%': Name: {$catName->name}, Slug: {$catName->slug}, Status: {$catName->status}<br>";
        } else {
            $out .= "No category found by Name LIKE '%পিএসসি%'!<br>";
        }
    }
    return $out;
});


Route::any('{slug}', [WebsiteController::class, 'fetcher'])->name('slug.handle')->where('slug', '.*');

