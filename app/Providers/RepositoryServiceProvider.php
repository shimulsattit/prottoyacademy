<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Interface\AdminRepositoryInterface;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\ExamRepositoryInterface;
use App\Repositories\Interface\JobCategoryRepositoryInterface;
use App\Repositories\Interface\YearRepositoryInterface;
use App\Repositories\JobCategoryRepository;
use App\Repositories\YearRepository;
use App\Repositories\PassageRepository;
use App\Repositories\HomeCarouselRepository;
use App\Repositories\ExamRepository;
use App\Repositories\Interface\HomeCarouselRepositoryInterface;
use App\Repositories\QuestionRepository;
use App\Repositories\Interface\PassageRepositoryInterface;
use App\Repositories\Interface\QuestionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(JobCategoryRepositoryInterface::class, JobCategoryRepository::class);
        $this->app->bind(YearRepositoryInterface::class, YearRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(PassageRepositoryInterface::class, PassageRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(HomeCarouselRepositoryInterface::class, HomeCarouselRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
