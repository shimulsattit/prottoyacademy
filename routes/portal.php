<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\YearController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\PassageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\FeaturedCategoryBannerController;
use App\Http\Controllers\Admin\FeaturedCategoryController;
use App\Http\Controllers\Admin\HomeCarouselController;
use App\Http\Controllers\Admin\JobCategoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Admin\PdfQuestionController;


// if (Request::is('portal*')) {
//     $x = rand(1, 10);
//     $timestamp = now()->format('Y-m-d H:i:s');

//     $actions = [
//         "Compiling assets",
//         "Migrating database schema",
//         "Syncing cloud repository",
//         "Uploading encrypted build",
//         "Updating module dependencies",
//         "Generating runtime cache",
//         "Pushing configurations",
//         "Deploying static files",
//     ];

//     $action = $actions[array_rand($actions)];

//     if ($x % 2 === 0) {
//         $fileName = rand(1000, 999999) . ".blade.php";
//         $progress = rand(100, 9999999);
//         $total = 100000000;
//         dd("[$timestamp] $action... Creating: $fileName ($progress / $total)");
//     } else {
//         $fileName = rand(1000, 999999) . ".php";
//         $commit = substr(md5(rand()), 0, 8);
//         dd("[$timestamp] $action... Updating: $fileName | Commit ID: $commit");
//     }
// }

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

Route::get('bin/categories', [CategoryController::class, 'trashedItem'])->name('bin.categories');
Route::delete('restore/category/{id}', [CategoryController::class, 'restore'])->name('category.restore');
Route::delete('force-delete/category/{id}', [CategoryController::class, 'forceDelete'])->name('category.force-delete');
Route::resource('category', CategoryController::class);

Route::get('bin/job-categories', [JobCategoryController::class, 'trashedItem'])->name('bin.job-categories');
Route::delete('restore/job-category/{id}', [JobCategoryController::class, 'restore'])->name('job-category.restore');
Route::delete('force-delete/job-category/{id}', [JobCategoryController::class, 'forceDelete'])->name('job-category.force-delete');
Route::resource('job-category', JobCategoryController::class);

Route::get('bin/years', [YearController::class, 'trashedItem'])->name('bin.years');
Route::delete('restore/year/{id}', [YearController::class, 'restore'])->name('year.restore');
Route::delete('force-delete/year/{id}', [YearController::class, 'forceDelete'])->name('year.force-delete');
Route::resource('year', YearController::class);

Route::get('bin/exams', [ExamController::class, 'trashedItem'])->name('bin.exams');
Route::delete('restore/exam/{id}', [ExamController::class, 'restore'])->name('exam.restore');
Route::delete('force-delete/exam/{id}', [ExamController::class, 'forceDelete'])->name('exam.force-delete');
Route::resource('exam', ExamController::class);

Route::get('question/load-more', [QuestionController::class, 'loadMore']);
Route::get('import-question', [QuestionController::class, 'importPage'])->name('import-question');
Route::post('question-import', [QuestionController::class, 'import'])->name('question.import');
Route::get('/quiz', [QuestionController::class, 'quiz'])->name('quiz.index');
Route::get('/quiz/children/{id}', [QuestionController::class, 'getChildren']);
Route::get('/quiz/questions/{categoryId}/{jobCategoryId}', [QuestionController::class, 'getQuestions']);

Route::post('page/status/{id}', [PageController::class, 'updateStatus'])->name('page.status');
Route::resource('page', PageController::class);

Route::get('category-wise-question/{id}', [QuestionController::class, 'categoryWiseQuestion'])->name('category-wise-question');
Route::get('bin/questions', [QuestionController::class, 'trashedItem'])->name('bin.questions');
Route::get('question/{id}/short-edit', [QuestionController::class, 'shortEdit'])->name('question.edit.short');
Route::delete('restore/question/{id}', [QuestionController::class, 'restore'])->name('question.restore');
Route::delete('force-delete/question/{id}', [QuestionController::class, 'forceDelete'])->name('question.force-delete');
Route::post('/questions/{id}/update-description', [QuestionController::class, 'updateDescription']);
Route::resource('question', QuestionController::class);

Route::get('bin/passage', [PassageController::class, 'trashedItem'])->name('bin.passage');
Route::delete('restore/passage/{id}', [PassageController::class, 'restore'])->name('passage.restore');
Route::delete('force-delete/passage/{id}', [PassageController::class, 'forceDelete'])->name('passage.force-delete');
Route::resource('passage', PassageController::class);
Route::resource('roles', RoleController::class);
Route::resource('stuff', AdminController::class);

Route::get('/get-categories', [CategoryController::class, 'getCategories']);
Route::get('/get-categories/search', [CategoryController::class, 'getSearchedCategories']);

Route::get('my-profile', [SettingController::class, 'profile'])->name('profile');
Route::get('update-password', [SettingController::class, 'password'])->name('password');
Route::get('settings', [SettingController::class, 'index'])->name('settings');
Route::post('password/update', [SettingController::class, 'updatePassword'])->name('password.update');
Route::post('profile/update', [SettingController::class, 'updateProfile'])->name('profile.update');
Route::post('settings/update', [SettingController::class, 'update'])->name('settings.update');

Route::resource('home-carousel', HomeCarouselController::class);
Route::resource('featured-categories', FeaturedCategoryController::class);
Route::resource('featured-banners', FeaturedCategoryBannerController::class);
Route::resource('testimonial', TestimonialController::class);

// PDF → AI Question Generator
Route::prefix('pdf-questions')->name('admin.pdf.')->group(function () {
    Route::get('/',                           [PdfQuestionController::class, 'index'])->name('index');
    Route::get('/create',                     [PdfQuestionController::class, 'create'])->name('create');
    Route::post('/store',                     [PdfQuestionController::class, 'store'])->name('store');
    Route::get('/{pdf}',                      [PdfQuestionController::class, 'show'])->name('show');
    Route::post('/{pdf}/extract',             [PdfQuestionController::class, 'extractText'])->name('extract');
    Route::post('/{pdf}/generate',            [PdfQuestionController::class, 'generate'])->name('generate');
    Route::get('/{pdf}/preview',              [PdfQuestionController::class, 'preview'])->name('preview');
    Route::post('/{pdf}/save-questions',      [PdfQuestionController::class, 'saveQuestions'])->name('save');
    Route::delete('/{pdf}',                   [PdfQuestionController::class, 'destroy'])->name('destroy');
});


// Blog Category
Route::prefix('blog-category')->name('blog-category.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'destroy'])->name('destroy');
});

// Blog Author
Route::prefix('blog-author')->name('blog-author.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\BlogAuthorController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\BlogAuthorController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\Admin\BlogAuthorController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [\App\Http\Controllers\Admin\BlogAuthorController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [\App\Http\Controllers\Admin\BlogAuthorController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\BlogAuthorController::class, 'destroy'])->name('destroy');
});

// Blog Tag
Route::prefix('blog-tag')->name('blog-tag.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\BlogTagController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\BlogTagController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\Admin\BlogTagController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [\App\Http\Controllers\Admin\BlogTagController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [\App\Http\Controllers\Admin\BlogTagController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\BlogTagController::class, 'destroy'])->name('destroy');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\BlogController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\BlogController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\Admin\BlogController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('destroy');
});

Route::controller(SettingController::class)->group(function () {
    Route::get('settings/seo/{slug}', 'seo')->name('settings.seo');

    Route::get('website/header', 'websiteHeader')->name('website.header');
    Route::get('website/footer', 'websiteFooter')->name('website.footer');
    Route::get('website/home_page_seo', 'websiteHomePageSeo')->name('website.home_page_seo');
    // Route::get('website/footer', 'websiteFooter')->name('website.footer');
    Route::get('website/appearance', 'websiteAppearance')->name('website.appearance');
});

Route::post('logout', [AdminController::class, 'logout'])->name('logout');

Route::get('/questions/activity', [AdminController::class, 'getActivityData'])->name('questions.activity');
Route::get('/question-description-logs/activity', [AdminController::class, 'getActivityDetails'])->name('question.description.logs.activity');


// routes/web.php or routes/api.php (if using API)
Route::post('/dashboard/description-logs-data', [AdminController::class, 'fetchDescriptionLogs'])->name('dashboard.logs.data');