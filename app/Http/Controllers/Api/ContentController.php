<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobCategory;
use App\Models\Question;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function categories()
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 1)
            ->withCount('children')
            ->get();

        return response()->json([
            'status' => true,
            'categories' => $categories
        ]);
    }

    public function subCategories($id)
    {
        $category = Category::findOrFail($id);
        $children = Category::where('parent_id', $id)
            ->where('status', 1)
            ->withCount('children')
            ->get();
        
        $jobCategories = JobCategory::where('category_id', $id)
            ->where('status', 1)
            ->get();

        return response()->json([
            'status' => true,
            'category' => $category,
            'sub_categories' => $children,
            'job_categories' => $jobCategories
        ]);
    }

    public function jobCategoryDetails($slug)
    {
        $jobCategory = JobCategory::where('slug', $slug)->firstOrFail();
        
        $activeQuestions = $jobCategory->questions()->where('status', 1)->count();
        
        return response()->json([
            'status' => true,
            'job_category' => $jobCategory,
            'total_questions' => $activeQuestions,
            'duration' => $activeQuestions, // 1 min per question
            'total_marks' => $activeQuestions
        ]);
    }

    public function questions(Request $request)
    {
        $query = Question::with('options')->where('status', 1);

        if ($request->has('job_category_id')) {
            $query->where('job_category_id', $request->job_category_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $questions = $query->latest()->paginate(20);

        return response()->json([
            'status' => true,
            'questions' => $questions
        ]);
    }
}
