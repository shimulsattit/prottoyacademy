<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Passage;
use App\Models\Question;
use App\Services\CategoryService;
use App\Services\JobCategoryService;
use App\Services\QuestionService;
use App\Services\YearService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller
{
    protected $questionService;
    protected $categoryService;
    protected $jobCategoryService;
    protected $yearService;

    public function __construct(
        QuestionService $questionService,
        CategoryService $categoryService,
        JobCategoryService $jobCategoryService,
        YearService $yearService
    ) {
        $this->questionService = $questionService;
        $this->categoryService = $categoryService;
        $this->jobCategoryService = $jobCategoryService;
        $this->yearService = $yearService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->questionService->dataTable($request->category);
        }

        $category = null;
        if($request->category_id) {
            $category = $this->categoryService->find($request->category_id);
        }

        return view('portal.question.index', compact('category'));
    }

    public function categoryWiseQuestion($categoryId)
    {
        $category = $this->categoryService->find($categoryId);
        if(!$category || ($category->parent_id != 9 && $category->parent_id != 64 && $category->parent_id != 337 && $category->parent_id != 312 && $category->parent_id != 783) ) {
            return redirect()->route('portal.dashboard');
        }

        $job_categories = $this->jobCategoryService->all()->where('category_id', $category->id);
        $years = $this->yearService->all()->select('id', 'name');

        return view('portal.question.category_wise', compact('category', 'job_categories', 'years'));
    }

    /**
     * Display a listing of the deleted resource.
     */
    public function trashedItem(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('recycle_bin.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->questionService->binDataTable();
        }

        return view('portal.question.bin');
    }

    public function importPage()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.import') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.question.import');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if (isset($request->parent_id)) {
            $categories = isset($request->parent_id) ? Category::where('status', 1)->where('parent_id', $request->parent_id)->select('id', 'name')->get() : Category::select('id', 'name')->where('status', 1)->get();

            return response()->json(['subs' => $categories]);
        }

        return view('portal.question.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->questionService->store($request);
    }
    
    public function import(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.import') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->questionService->import($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->questionService->find($id);
        return view('portal.question.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->questionService->find($id);
        if(!$model) {
            return redirect()->route('portal.question.index');
        }
        return view('portal.question.edit', compact('model'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->questionService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('question.delete') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->questionService->destroy($id);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        return $this->questionService->restore($id);
    }

    /**
     * Force Delete the specified resource from storage.
     */
    public function forceDelete(string $id)
    {
        return $this->questionService->forceDelete($id);
    }

    public function updateDescription(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $question = Question::findOrFail($id);
        $question->content = $request->content;
        $question->save();

        if($question && $request->content != '') {
            $log = new \App\Models\QuestionDescriptionLog();
            $log->type = 'question';
            $log->question_id = $id;
            $log->admin_id = auth()->guard('admin')->user()->id;
            $log->description = $request->content;
            $log->save();
        }

        return response()->json(['message' => 'Updated successfully']);
    }


    public function loadMore(Request $request)
    {
        $page = $request->input('page', 1);
        $categoryId = $request->categoryId;
        $questions = Question::with(['category', 'job_category', 'exam'])->where('category_id', $categoryId)->latest()->skip($page * 20)->take(20)->get();
        return view('portal.question.data', compact('questions'))->render();
    }

    public function shortEdit(string $id)
    {
        $question = $this->questionService->findById($id);
        return view('portal.question.short-edit', compact('question'));
    }

    public function quiz()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('portal.question.quiz.index', compact('categories'));
    }

    public function getChildren($id)
    {
        $children = Category::where('parent_id', $id)->get();
        return response()->json($children);
    }

    private function getAllChildCategoriesWithName($parentId)
    {
        $categories = Category::where('parent_id', $parentId)->get();
        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
            $result = array_merge($result, $this->getAllChildCategoriesWithName($category->id));
        }

        // if ($parent = Category::find($parentId)) {
        //     array_unshift($result, [
        //         'id' => $parent->id,
        //         'name' => $parent->name,
        //     ]);
        // }

        return $result;
    }

    public function getQuestions(Request $request, $categoryId, $jobCategoryId)
    {
        $limit = 20;
        $offset = $request->get('offset', 0);

        // Get category tree
        $categories = $this->getAllChildCategoriesWithName($categoryId);

        // Always include main category ID
        $categoryIds = collect($categories)->pluck('id')->push($categoryId)->unique()->toArray();

        // Fetch only categories that actually have questions
        $validCategoryIds = Question::whereIn('category_id', $categoryIds)
            ->where('job_category_id', $jobCategoryId)
            ->select('category_id')
            ->distinct()
            ->pluck('category_id')
            ->toArray();

        // Now build category array with name only for valid ones
        $categories = Category::whereIn('id', $validCategoryIds)
            ->get(['id', 'name'])
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            })
            ->values()
            ->toArray();


        $categoryIds = array_column($categories, 'id');
        array_push($categoryIds, $categoryId);

        // Fetch all questions by category and job category
        $questions = Question::with(['options', 'passage', 'category', 'job_category', 'exam'])
            ->whereIn('category_id', $categoryIds)
            ->where('job_category_id', $jobCategoryId)
            ->orderBy('id', 'desc')
            // ->skip($offset)
            // ->take($limit)
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'uuid' => $q->uuid,
                    'type' => $q->question_type,
                    'category_id' => $q->category_id,
                    'passage_id' => $q->passage_id,
                    'question' => $this->replacePTags($q->question),
                    'correct_answer' => $q->correct_answer,
                    'content' => $q->content,
                    'options' => [
                        $this->replacePTags($q->options ? $q->options->option_one : ''),
                        $this->replacePTags($q->options ? $q->options->option_two : ''),
                        $this->replacePTags($q->options ? $q->options->option_three : ''),
                        $this->replacePTags($q->options ? $q->options->option_four : ''),
                        $q->options && $q->options->option_five !== '' ? $this->replacePTags($q->options->option_five) : null,
                    ],
                    'category_name' => $q->category?->name,
                    'job_category_name' => $q->job_category?->name,
                    'exam_name' => $q->exam?->name,
                    'root_category_name' => ($q->category?->breadcrumb()->get(1) && $q->category?->breadcrumb()->get(1)->name != $q->category?->breadcrumb()->first()->name) 
                        ? ($q->category?->breadcrumb()->first()->name . ': ' . $q->category?->breadcrumb()->get(1)->name)
                        : $q->category?->breadcrumb()->first()->name,
                    'passage_name' => $q->passage?->name,
                    'passage_text' => $q->passage?->passage,
                ];
            });

        // Group by category
        $categoryGrouped = $questions->groupBy('category_id');
        $final = [];

        foreach ($categoryGrouped as $catId => $questionList) {
            $category = collect($categories)->firstWhere('id', (int) $catId);
            if (!$category) continue;

            // Group by passage_id within this category
            $passageGroups = $questionList->groupBy('passage_id');
            $groupData = [];

            foreach ($passageGroups as $passageId => $questionsInPassage) {
                $groupData[] = [
                    'passage_id' => $passageId,
                    'passage_name' => $questionsInPassage->first()['passage_name'] ?? '',
                    'passage_text' => $questionsInPassage->first()['passage_text'] ?? '',
                    'questions' => array_values($questionsInPassage->map(function ($q) {
                        return collect($q)->except(['passage_name', 'passage_text']);
                    })->toArray()),
                ];
            }

            $final[] = [
                'category_id' => $catId,
                'category_name' => $category['name'],
                'groups' => $groupData,
            ];
        }

        return response()->json([
            'grouped_questions' => $final,
            'categories' => $categories,
            'has_more' => Question::whereIn('category_id', $categoryIds)
                ->where('job_category_id', $jobCategoryId)
                ->count() > ($offset + $limit),
        ]);
    }



    private function replacePTags($text) {
        return str_replace(['<p>', '</p>'], ['<span>', '</span>'], $text);
    }
    
}
