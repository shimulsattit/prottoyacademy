<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Exam;
use App\Models\JobCategory;
use App\Models\Page;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = [];
        $now = Carbon::now()->toAtomString();

        // 1. Static Base Routes
        $staticRoutes = [
            ['route' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['route' => '/blogs', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['route' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['route' => '/login', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['route' => '/register', 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($staticRoutes as $route) {
            $urls[] = [
                'loc' => url($route['route']),
                'lastmod' => $now,
                'changefreq' => $route['changefreq'],
                'priority' => $route['priority']
            ];
        }

        // Helper function to safely format dates
        $formatDate = function($date) {
            return $date ? Carbon::parse($date)->toAtomString() : Carbon::now()->toAtomString();
        };

        // 2. Custom Pages (Page Model)
        $pages = Page::where('status', 1)->get(['slug', 'updated_at']);
        foreach ($pages as $page) {
            if ($page->slug) {
                $urls[] = [
                    'loc' => url($page->slug),
                    'lastmod' => $formatDate($page->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8'
                ];
            }
        }

        // 3. Blog Categories
        $blogCategories = BlogCategory::where('status', 1)->get(['slug', 'updated_at']);
        foreach ($blogCategories as $blogCategory) {
            if ($blogCategory->slug) {
                $urls[] = [
                    'loc' => url($blogCategory->slug),
                    'lastmod' => $formatDate($blogCategory->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        }

        // 4. Blog Authors
        $blogAuthors = BlogAuthor::where('status', 1)->get(['slug', 'updated_at']);
        foreach ($blogAuthors as $blogAuthor) {
            if ($blogAuthor->slug) {
                $urls[] = [
                    'loc' => url($blogAuthor->slug),
                    'lastmod' => $formatDate($blogAuthor->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.6'
                ];
            }
        }

        // 5. Blogs (Articles)
        $blogs = Blog::where('status', 1)->get(['slug', 'updated_at']);
        foreach ($blogs as $blog) {
            if ($blog->slug) {
                $urls[] = [
                    'loc' => url($blog->slug),
                    'lastmod' => $formatDate($blog->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8'
                ];
            }
        }

        // 6. Categories (Academic Subjects)
        $categories = Category::where('status', 1)->get(['slug', 'updated_at']);
        foreach ($categories as $category) {
            if ($category->slug) {
                $urls[] = [
                    'loc' => url($category->slug),
                    'lastmod' => $formatDate($category->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.9'
                ];
            }
        }

        // 7. Job Categories
        $jobCategories = JobCategory::where('status', 1)->get(['slug', 'updated_at']);
        foreach ($jobCategories as $jobCategory) {
            if ($jobCategory->slug) {
                $urls[] = [
                    'loc' => url($jobCategory->slug),
                    'lastmod' => $formatDate($jobCategory->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8'
                ];
            }
        }

        // 8. Exams
        $exams = Exam::where('status', 1)->get(['slug', 'updated_at']);
        foreach ($exams as $exam) {
            if ($exam->slug) {
                $urls[] = [
                    'loc' => url('exam/' . $exam->slug),
                    'lastmod' => $formatDate($exam->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.9'
                ];
            }
        }

        return response()->view('web.sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}
