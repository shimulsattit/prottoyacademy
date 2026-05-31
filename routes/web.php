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
    
    $slug1 = "পিএসসি-ও-অন্যান্য-পরীক্ষা";
    $cat1 = \App\Models\Category::where('slug', $slug1)->first();
    if ($cat1) {
        $out .= "Category 27 exists by hyphenated slug! Name: {$cat1->name}, Slug: {$cat1->slug}<br>";
    } else {
        $out .= "Category 27 NOT found by hyphenated slug '{$slug1}'!<br>";
        $cat1_any = \App\Models\Category::where('name', 'like', '%পিএসসি%')->get();
        foreach ($cat1_any as $c) {
            $out .= " - Found in DB: ID: {$c->id}, Name: '{$c->name}', Slug: '{$c->slug}', Status: {$c->status}<br>";
        }
    }
    
    $out .= "<br>";
    
    $slug2 = "প্রাথমিক-সহকারী-শিক্ষক-নিয়োগ-পরীক্ষা";
    $cat2 = \App\Models\Category::where('slug', $slug2)->first();
    if ($cat2) {
        $out .= "Category 23 exists by hyphenated slug! Name: {$cat2->name}, Slug: {$cat2->slug}<br>";
    } else {
        $out .= "Category 23 NOT found by hyphenated slug '{$slug2}'!<br>";
        $cat2_any = \App\Models\Category::where('name', 'like', '%প্রাথমিক সহকারী%')->get();
        foreach ($cat2_any as $c) {
            $out .= " - Found in DB: ID: {$c->id}, Name: {$c->name}, Slug: {$c->slug}, Status: {$c->status}<br>";
        }
    }
    
    $out .= "<br>";
    
    $slug3 = "বাংলাদেশ-আনসার-ও-গ্রাম-প্রতিরক্ষা-বাহিনী-সাঁট-লিপিকার-কাম-কম্পিউটার-অপারেটর,সাঁট-মুদ্রাক্ষরিক-কাম-কম্পিউটার-অপারেটর,থানা/উপজেলা-প্রশিক্ষক,উপজেলা/থানা-মহিলা-প্রশিক্ষিকা,পেস্টিং-সহকারী,প্রুফ-রিডার,-অফিস-সহকারী-(31-05-2025)";
    $jc = \App\Models\JobCategory::where('slug', $slug3)->first();
    if ($jc) {
        $out .= "JobCategory 141 exists by hyphenated slug! Name: {$jc->name}, Slug: {$jc->slug}<br>";
    } else {
        $out .= "JobCategory 141 NOT found by exact hyphenated slug!<br>";
        // Find with variants or search by name
        $jc_any = \App\Models\JobCategory::where('name', 'like', '%আনসার%')->get();
        foreach ($jc_any as $j) {
            $out .= " - Found in DB: ID: {$j->id}, Name: {$j->name}, Slug: {$j->slug}, Status: {$j->status}<br>";
        }
    }
    
    return $out;
});



Route::any('{slug}', [WebsiteController::class, 'fetcher'])->name('slug.handle')->where('slug', '.*');
