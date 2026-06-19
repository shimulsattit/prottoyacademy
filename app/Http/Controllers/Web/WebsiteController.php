<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Category;
use App\Models\FeaturedCategory;
use App\Models\HomeCarousel;
use App\Models\JobCategory;
use App\Models\Page;
use App\Models\Question;
use App\Models\Student;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    public function index()
    {
        $homeSliders = HomeCarousel::where('status', 1)->get();
        $featuredCategories = FeaturedCategory::where('status', 1)->get();
        $featuredTestimonial = Testimonial::where('status', 1)->where('show_on_home_page', 1)->get();
        $featuredBlogs = Blog::where('status', 1)->where('featured', 1)->get();
        return view('web.homepage', compact('homeSliders', 'featuredBlogs', 'featuredCategories', 'featuredTestimonial'));
    }

    public function login()
    {
        if(auth()->guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('web.login');
    }

    public function contact()
    {
        return view('web.contact');
    }

    public function blogs()
    {
        $blogs = Blog::where('status', 1)->orderBy('id', 'DESC')->paginate(15);
        return view('web.blogs', compact('blogs'));
    }

    public function register()
    {
        if(auth()->guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('web.register');
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        if (auth()->guard('student')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'goto' => route('student.dashboard')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        auth()->guard('student')->login($student);

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'goto' => route('student.dashboard')
        ]);
    }

    public function forgetPassword()
    {
        if(auth()->guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('web.forget-password');
    }

    public function postForgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:students,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        // Here you would typically generate a password reset token and send an email to the user.
        $code = rand(100000, 999999);
        Session::put('password_reset_code', $code);
        Session::put('password_reset_email', $request->email);
        // For simplicity, we'll just return a success message.

        return response()->json([
            'status' => true,
            'goto' => route('verify.otp'),
            'message' => 'We have sent a password reset code to your email.'
        ]);
    }

    public function verifyOtp()
    {
        if(auth()->guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        if(!Session::has('password_reset_code') || !Session::has('password_reset_email')) {
            return redirect()->route('forget.password')->withErrors(['email' => 'Invalid password reset session. Please try again.']);
        }

        return view('web.verify-otp');
    }

    public function postVerifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $sessionCode = Session::get('password_reset_code');
        $email = Session::get('password_reset_email');

        if ($request->code == $sessionCode) {
            // OTP is correct, redirect to password reset page or allow password reset
            // For simplicity, we'll just clear the session and return a success message.
            Session::forget(['password_reset_code']);
            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully. You can now reset your password.',
                'goto' => route('password.reset') // You would typically redirect to a password reset form
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }
    }

    public function resetPassword()
    {
        return view('web.reset-password');
    }

    public function postResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        // Here you would typically find the user by the email stored in session and update their password.
        $email = Session::get('password_reset_email');
        $student = Student::where('email', $email)->first();

        if ($student) {
            $student->password = bcrypt($request->password);
            $student->save();

            // Clear the session after resetting the password
            Session::forget('password_reset_email');

            return response()->json([
                'status' => true,
                'message' => 'Password reset successful. You can now log in with your new password.',
                'goto' => route('login')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found. Please try again.'
            ]);
        }
    }

    public function fetcher($slug, Request $request, $index = 0)
    {
        $slug = trim($slug, '/');

        // Redirect if slug has spaces to the correct hyphenated SEO URL
        if (str_contains($slug, ' ')) {
            $normalizedSlug = preg_replace('/-+/', '-', str_replace(' ', '-', $slug));
            return redirect()->route('slug.handle', ['slug' => $normalizedSlug], 301);
        }

        $models = ['Category', 'Blog', 'BlogCategory', 'BlogAuthor', 'BlogTag', 'Page', 'JobCategory'];

        if ($index >= count($models)) {
            return view('errors.404');
        }

        // Get the current model name
        $model = $models[$index];
        
        $item = null;
        if (in_array($model, ['Category', 'Blog', 'BlogCategory', 'BlogAuthor', 'Page', 'JobCategory'])) {
            $item = $this->findModelBySlug("App\\Models\\" . $model, $slug);
        }

        if ($model == 'Category' && $item) {
            $category = $item;

            if ($category) {
                // Determine if this is a Current Affairs category (Category ID 312 or its descendants)
                $caCategoryIds = [312];
                $caLevel1 = Category::where('parent_id', 312)->pluck('id')->toArray();
                $caCategoryIds = array_merge($caCategoryIds, $caLevel1);
                if (!empty($caLevel1)) {
                    $caLevel2 = Category::whereIn('parent_id', $caLevel1)->pluck('id')->toArray();
                    $caCategoryIds = array_merge($caCategoryIds, $caLevel2);
                }
                
                $isCurrentAffairs = in_array($category->id, $caCategoryIds);

                if ($this->isAcademyCategory($category)) {
                    // Resolve the correct Class category for stats calculation
                    $classCategory = $category;
                    $selectedClassId = null;
                    $selectedSubjectId = null;
                    $selectedChapterId = null;

                    if ($category->id != 783) {
                        $current = $category;
                        $path = [];
                        while ($current && $current->id != 783) {
                            $path[] = $current;
                            $current = $current->parent_id ? Category::find($current->parent_id) : null;
                        }
                        if (!empty($path)) {
                            $classCat = end($path);
                            $classCategory = $classCat;
                            $selectedClassId = $classCat->id;
                            
                            if ($category->id != $classCat->id) {
                                $selectedChapterId = $category->id;
                                $selectedSubjectId = Question::where('category_id', $category->id)
                                    ->where('status', 1)
                                    ->value('job_category_id');
                            }
                        }
                    }

                    $stats = $this->getAcademyStats($classCategory);
                    return view('web.academy', compact('category', 'stats', 'selectedClassId', 'selectedSubjectId', 'selectedChapterId'));
                }

                if ($isCurrentAffairs) {
                    // Fetch all job categories recursively under this category
                    $targetCategoryIds = [$category->id];
                    $subLevel1 = Category::where('parent_id', $category->id)->pluck('id')->toArray();
                    $targetCategoryIds = array_merge($targetCategoryIds, $subLevel1);
                    if (!empty($subLevel1)) {
                        $subLevel2 = Category::whereIn('parent_id', $subLevel1)->pluck('id')->toArray();
                        $targetCategoryIds = array_merge($targetCategoryIds, $subLevel2);
                    }
                    $allJobCategories = JobCategory::with('questions')->whereIn('category_id', $targetCategoryIds)->where('status', 1)->get();
                } else {
                    $allJobCategories = JobCategory::with('questions')->where('categorY_id', $category->id)->where('status', 1)->get();
                }

                $sortedCategories = $allJobCategories->sortByDesc('id');
                
                $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
                $perPage = 100;
                $currentItems = $sortedCategories->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
                $jobCategories = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $sortedCategories->count(), $perPage, $currentPage, [
                    'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()
                ]);


                // If Current Affairs, show the new interactive view
                if ($isCurrentAffairs) {
                    $stats = $this->getCurrentAffairsStats($category);
                    return view('web.current-affairs', compact('category', 'stats'));
                }

                // Check if this category has child categories
                $childCategories = Category::where('parent_id', $category->id)
                                            ->where('status', 1)
                                            ->orderByRaw('FIELD(id, 73, 74, 72, 71, 70, 66, 65) DESC')
                                            ->orderBy('id', 'desc')
                                            ->paginate(100)->onEachSide(1);

                // If child category exists → Show child list
                if ($childCategories->count() > 0) {
                    return view('web.category-wise', [
                        'category'        => $category,
                        'childCategories' => $childCategories,
                        'questions'       => collect(), // empty
                        'jobCategories' => $jobCategories
                    ]);
                }

                $questions = Question::where('category_id', $category->id)
                     ->where('status', 1)
                     ->with(['options', 'category', 'job_category'])
                     ->orderBy('id', 'desc')
                     ->get()
                     ->map(function ($q) {
                         return [
                             'id' => $q->id,
                             'uuid' => $q->uuid,
                             'type' => $q->question_type,
                             'question' => html_entity_decode($q->question),
                             'correct_answer' => html_entity_decode($q->correct_answer),
                             'content' => html_entity_decode($q->content),
                             'options' => [
                                 $q->options?->option_one ? html_entity_decode($q->options->option_one) : '',
                                 $q->options?->option_two ? html_entity_decode($q->options->option_two) : '',
                                 $q->options?->option_three ? html_entity_decode($q->options->option_three) : '',
                                 $q->options?->option_four ? html_entity_decode($q->options->option_four) : '',
                                 $q->options?->option_five ? html_entity_decode($q->options->option_five) : null,
                             ],
                             'category_name' => $q->category?->name,
                             'category_slug' => $q->category?->slug,
                             'exam_name' => $q->job_category?->name,
                             'exam_slug' => $q->job_category?->slug,
                             'job_category_name' => $q->job_category?->category?->name,
                             'job_category_slug' => $q->job_category?->category?->slug,
                         ];
                     })
                     ->values();

                return view('web.category-wise', [
                    'category'        => $category,
                    'childCategories' => collect(),
                    'questions'       => $questions,
                    'jobCategories' => $jobCategories
                ]);
            }

            return $this->fetcher($slug, $request, $index + 1);
        } elseif ($model == 'Blog' && $item) {
            $blog = $item;

            if ($blog) {
                $tags = BlogTag::where('status', 1)->get();
                $blogCategories = BlogCategory::with('blogs')->where('status', 1)->get();
                $relatedBlogs = Blog::where('blog_category_id', $blog->category_id)->whereNot('id', $blog->id)->where('status', 1)->take(5)->get();
                return view('web.blog-details', compact(
                    'blog',
                    'blogCategories',
                    'relatedBlogs',
                    'tags'
                ));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }

        } elseif ($model == 'BlogCategory' && $item) {
            $category = $item;
            if ($category) {

                $blogs = Blog::where('blog_category_id', $category->id)->where('status', 1)->paginate(15);

                return view('web.blog-category-listing', compact('category', 'blogs'));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }

        } elseif ($model == 'BlogAuthor' && $item) {
            $author = $item;
            if ($author) {

                $blogs = Blog::where('blog_author_id', $author->id)->where('status', 1)->paginate(15);

                return view('web.blog-author-listing', compact('author', 'blogs'));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }

        } elseif ($model == 'Page' && $item) {
            $model = $item;
            if($model) {
                return view('web.page', compact('model'));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }
        } elseif ($model == 'JobCategory' && $item) {
            $model = $item;

            if($model) {

                $mainCategory = Category::find($model->category_id);
                $breadcrumbs = $mainCategory->breadcrumb();

                // Get category tree
                $categories = $this->getAllChildCategoriesWithName($model->category_id);

                // Always include main category ID
                $categoryIds = collect($categories)->pluck('id')->push($model->category_id)->unique()->toArray();

                // Fetch only categories that actually have questions
                $validCategoryIds = Question::whereIn('category_id', $categoryIds)
                    ->where('job_category_id', $model->id)
                    ->select('category_id')
                    ->distinct()
                    ->pluck('category_id')
                    ->toArray();

                $targetCategoryIds = $validCategoryIds;

                // Now build category array with name and slug
                $categories = Category::whereIn('id', $targetCategoryIds)
                    ->get(['id', 'name', 'slug'])
                    ->map(function ($cat) {
                        return [
                            'id' => $cat->id,
                            'name' => $cat->name,
                            'slug' => $cat->slug,
                        ];
                    })
                    ->values()
                    ->toArray();


                $categoryIds = array_column($categories, 'id');
                array_push($categoryIds, $model->category_id);

                // Fetch all questions by category and job category
                $questions = Question::with(['options', 'passage', 'category', 'job_category', 'exam'])
                    ->whereIn('category_id', $categoryIds)
                    ->where('job_category_id', $model->id)
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
                            'question' => html_entity_decode($q->question),
                            'correct_answer' => html_entity_decode($q->correct_answer),
                            'content' => html_entity_decode($q->content),
                            'options' => [
                                $q->options && $q->options->option_one !== '' ? html_entity_decode($q->options->option_one) : '',
                                $q->options && $q->options->option_two !== '' ? html_entity_decode($q->options->option_two) : '',
                                $q->options && $q->options->option_three !== '' ? html_entity_decode($q->options->option_three) : '',
                                $q->options && $q->options->option_four !== '' ? html_entity_decode($q->options->option_four) : '',
                                $q->options && $q->options->option_five !== '' ? html_entity_decode($q->options->option_five) : null,
                            ],
                            'category_name' => $q->category?->name,
                            'category_slug' => $q->category?->slug,
                            'exam_name' => $q->job_category?->name,
                            'exam_slug' => $q->job_category?->slug,
                            'job_category_name' => $q->job_category?->category?->name,
                            'job_category_slug' => $q->job_category?->category?->slug,
                            'passage_name' => $q->passage?->name ? html_entity_decode($q->passage->name) : '',
                            'passage_text' => $q->passage?->passage ? html_entity_decode($q->passage->passage) : '',
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

                return view('web.question', compact(
                    'final',
                    'breadcrumbs',
                    'categories',
                    'model',
                    'mainCategory'
                ));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }
        } else {
            return $this->fetcher($slug, $request, $index + 1);
        }
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

    public function academyFilter(Request $request)
    {
        $academyId = 783;
        
        // Enforce that class_ids, subject_ids, and types are all present and non-empty
        if (!$request->has('class_ids') || !is_array($request->class_ids) || empty($request->class_ids) ||
            !$request->has('subject_ids') || !is_array($request->subject_ids) || empty($request->subject_ids) ||
            !$request->has('types') || !is_array($request->types) || empty($request->types)) {
            
            return response()->json([
                'questions' => [],
                'pagination' => [
                    'total' => 0,
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 20,
                ]
            ]);
        }
        
        // Get all category IDs under Academy hierarchy
        $academyCategoryIds = [$academyId];
        $level1 = Category::where('parent_id', $academyId)->pluck('id')->toArray();
        $academyCategoryIds = array_merge($academyCategoryIds, $level1);
        if (!empty($level1)) {
            $level2 = Category::whereIn('parent_id', $level1)->pluck('id')->toArray();
            $academyCategoryIds = array_merge($academyCategoryIds, $level2);
        }
        $academyCategoryIds = array_unique($academyCategoryIds);
        
        // Start Query
        $query = Question::with(['options', 'category', 'job_category', 'year', 'passage'])
            ->whereIn('category_id', $academyCategoryIds)
            ->where('status', 1)
            ->where(function($q) {
                $q->where('question_type', '!=', 'cq')
                  ->orWhereNull('passage_id')
                  ->orWhereIn('id', function($subQuery) {
                      $subQuery->selectRaw('MIN(id)')
                               ->from('questions')
                               ->where('question_type', 'cq')
                               ->whereNotNull('passage_id')
                               ->groupBy('passage_id');
                  });
            });
            
        // Filter by Chapters (Categories)
        if ($request->has('chapter_ids') && is_array($request->chapter_ids) && !empty($request->chapter_ids)) {
            $query->whereIn('category_id', $request->chapter_ids);
        } else {
            // Filter by Classes
            if ($request->has('class_ids') && is_array($request->class_ids) && !empty($request->class_ids)) {
                // Find all subcategories (chapters/subjects) recursively under the selected class category IDs (up to 3 levels deep)
                $classCategoryIds = $request->class_ids;
                $allCategoryIds = $classCategoryIds;
                
                $subLevel1 = Category::whereIn('parent_id', $classCategoryIds)->pluck('id')->toArray();
                if (!empty($subLevel1)) {
                    $allCategoryIds = array_merge($allCategoryIds, $subLevel1);
                    $subLevel2 = Category::whereIn('parent_id', $subLevel1)->pluck('id')->toArray();
                    if (!empty($subLevel2)) {
                        $allCategoryIds = array_merge($allCategoryIds, $subLevel2);
                    }
                }
                $targetCategoryIds = array_unique($allCategoryIds);
                
                $query->whereIn('category_id', $targetCategoryIds);
            }
        }
        
        // Filter by Subjects (Job Category)
        if ($request->has('subject_ids') && is_array($request->subject_ids) && !empty($request->subject_ids)) {
            $query->whereIn('job_category_id', $request->subject_ids);
        }
        
        // Filter by Search Query
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhereHas('category', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('job_category', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Calculate dynamic stats before filtering by question types
        $statsQuery = clone $query;
        $mcqCount = (clone $statsQuery)->where('question_type', 'mcq')->count();
        $cqCount = (clone $statsQuery)->where('question_type', 'cq')->count();
        $shortCount = (clone $statsQuery)->whereIn('question_type', ['short', 'short_answer'])->count();
        $totalQuestions = $mcqCount + $cqCount + $shortCount;
        
        $activeCategoryIds = (clone $statsQuery)->distinct()->pluck('category_id')->toArray();
        $chaptersCount = Category::whereIn('id', $activeCategoryIds)
            ->whereNotNull('parent_id')
            ->where('parent_id', '!=', 783)
            ->where('status', 1)
            ->count();
            
        $activeSubjectIds = (clone $statsQuery)->whereNotNull('job_category_id')->distinct()->pluck('job_category_id')->toArray();
        $subjectsCount = count($activeSubjectIds);
        
        $classesCount = 0;
        if (!empty($activeCategoryIds)) {
            $parentIds = Category::whereIn('id', $activeCategoryIds)->pluck('parent_id')->filter()->unique()->toArray();
            $classesCount = Category::where(function($q) use ($activeCategoryIds, $parentIds) {
                $q->whereIn('id', $activeCategoryIds)
                  ->orWhereIn('id', $parentIds);
            })->where('parent_id', 783)->where('status', 1)->count();
        }

        // Now filter by Types for the actual questions list
        if ($request->has('types') && is_array($request->types) && !empty($request->types)) {
            $mappedTypes = [];
            foreach ($request->types as $type) {
                if ($type === 'short') {
                    $mappedTypes[] = 'short_answer';
                    $mappedTypes[] = 'short';
                } else {
                    $mappedTypes[] = $type;
                }
            }
            $query->whereIn('question_type', $mappedTypes);
        }
        
        // Execute and Paginate
        if ($request->has('print_all') && $request->print_all == 1) {
            $questions = $query->orderBy('id', 'desc')->paginate(1000);
        } else {
            $questions = $query->orderBy('id', 'desc')->paginate(20);
        }
        
        // Format Questions for view
        $formatted = collect($questions->items())->map(function($q) {
            $opt = $q->options;

            // For creative questions (cq), load passage details and all sub-questions of the passage
            $passageText = null;
            $subQuestions = [];
            if ($q->question_type === 'cq' && $q->passage_id) {
                $passageText = $q->passage?->passage;
                $subQuestions = Question::where('passage_id', $q->passage_id)
                    ->orderBy('question_mark', 'asc')
                    ->get()
                    ->map(function($sub) {
                        return [
                            'id' => $sub->id,
                            'question' => $sub->question,
                            'question_mark' => $sub->question_mark,
                            'correct_answer' => $sub->correct_answer,
                        ];
                    })->toArray();
            }

            return [
                'id' => $q->id,
                'question' => $q->question,
                'question_type' => $q->question_type,
                'question_type_name' => $q->question_type === 'mcq' ? 'বহুনির্বাচনী' : ($q->question_type === 'cq' ? 'সৃজনশীল' : 'সংক্ষিপ্ত'),
                'hard_level' => $q->hard_level ?? 'easy',
                'hard_level_name' => $q->hard_level === 'medium' ? 'মাঝারি' : ($q->hard_level === 'hard' ? 'কঠিন' : 'সহজ'),
                'category_name' => $q->category?->name,
                'job_category_name' => $q->job_category?->name,
                'year_name' => $q->year?->name ? 'বোর্ড ' . $q->year->name : '',
                'correct_answer' => $q->correct_answer,
                'options' => $opt ? [
                    'option_a' => html_entity_decode($opt->option_one),
                    'option_b' => html_entity_decode($opt->option_two),
                    'option_c' => html_entity_decode($opt->option_three),
                    'option_d' => html_entity_decode($opt->option_four),
                    'option_e' => html_entity_decode($opt->option_five),
                ] : null,
                'edit_url' => route('portal.question.edit', $q->id),
                'view' => $q->view ?: 0,
                'passage_id' => $q->passage_id,
                'passage_text' => $passageText,
                'sub_questions' => $subQuestions
            ];
        });
        
        // Return JSON response
        return response()->json([
            'questions' => $formatted,
            'pagination' => [
                'total' => $questions->total(),
                'current_page' => $questions->currentPage(),
                'last_page' => $questions->lastPage(),
                'per_page' => $questions->perPage(),
            ],
            'stats' => [
                'total_questions' => $totalQuestions,
                'mcq_count' => $mcqCount,
                'cq_count' => $cqCount,
                'short_count' => $shortCount,
                'chapters_count' => $chaptersCount,
                'subjects_count' => $subjectsCount,
                'classes_count' => $classesCount
            ]
        ]);
    }

    public function academyIncrementView($id)
    {
        $question = Question::find($id);
        if ($question) {
            $question->view = ($question->view ?: 0) + 1;
            $question->save();
            return response()->json([
                'status' => true,
                'new_view' => $question->view
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Question not found'
        ], 404);
    }

    private function isAcademyCategory($category)
    {
        if (!$category) return false;
        if ($category->id == 783) {
            return true;
        }
        
        $parent = $category->parent_id ? Category::find($category->parent_id) : null;
        while ($parent) {
            if ($parent->id == 783) {
                return true;
            }
            $parent = $parent->parent_id ? Category::find($parent->parent_id) : null;
        }
        
        return false;
    }

    private function getAcademyStats($category)
    {
        if ($category->id == 783) {
            // Academy Root
            $classes = Category::where('parent_id', 783)->where('status', 1)->get();
            $classIds = $classes->pluck('id')->toArray();
            
            $chapters = Category::whereIn('parent_id', $classIds)->where('status', 1)->get();
            $chapterIds = $chapters->pluck('id')->toArray();
            
            $descendantIds = array_unique(array_merge([783], $classIds, $chapterIds));
            
            $mcqCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'mcq')->count();
            $cqCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
            $shortCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
            $totalQuestions = $mcqCount + $cqCount + $shortCount;
            
            $chaptersCount = $chapters->count();
            $classesCount = $classes->count();
            
            $subjectCategoryIds = $classIds;
        } else {
            // A specific Class category (e.g. Class 8)
            $classIds = [$category->id];
            
            $chapters = Category::where('parent_id', $category->id)->where('status', 1)->get();
            $chapterIds = $chapters->pluck('id')->toArray();
            
            $descendantIds = array_unique(array_merge($classIds, $chapterIds));
            
            $mcqCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'mcq')->count();
            $cqCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
            $shortCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
            $totalQuestions = $mcqCount + $cqCount + $shortCount;
            
            $chaptersCount = $chapters->count();
            $classesCount = 1;
            
            $subjectCategoryIds = $classIds;
        }
        
        // Classes sidebar
        $classesSidebar = Category::where('parent_id', 783)->where('status', 1)->get()->map(function($c) {
            $classSubIds = [$c->id];
            $subs = Category::where('parent_id', $c->id)->pluck('id')->toArray();
            $classSubIds = array_merge($classSubIds, $subs);
            
            $mcqCount = Question::whereIn('category_id', $classSubIds)->where('status', 1)->where('question_type', 'mcq')->count();
            $cqCount = Question::whereIn('category_id', $classSubIds)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::whereIn('category_id', $classSubIds)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
            $shortCount = Question::whereIn('category_id', $classSubIds)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
            $count = $mcqCount + $cqCount + $shortCount;
            
            return [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'count' => $count
            ];
        })->filter(function($c) {
            return $c['count'] > 0;
        })->values()->toArray();
        
        // Subjects sidebar with nested chapters
        $subjects = JobCategory::whereIn('category_id', $subjectCategoryIds)->where('status', 1)->get()->map(function($jc) {
            $mcqCount = Question::where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'mcq')->count();
            $cqCount = Question::where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
            $shortCount = Question::where('job_category_id', $jc->id)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
            $count = $mcqCount + $cqCount + $shortCount;
            
            // Find chapters for this subject
            $chapterIds = Question::where('job_category_id', $jc->id)
                ->where('status', 1)
                ->select('category_id')
                ->distinct()
                ->pluck('category_id')
                ->toArray();
                
            $chapters = Category::whereIn('id', $chapterIds)
                ->whereNotNull('parent_id')
                ->where('parent_id', '!=', 783)
                ->where('status', 1)
                ->get()
                ->map(function($chap) use ($jc) {
                    $chapMcqCount = Question::where('category_id', $chap->id)->where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'mcq')->count();
                    $chapCqCount = Question::where('category_id', $chap->id)->where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::where('category_id', $chap->id)->where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
                    $chapShortCount = Question::where('category_id', $chap->id)->where('job_category_id', $jc->id)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
                    $chapCount = $chapMcqCount + $chapCqCount + $chapShortCount;
                    
                    return [
                        'id' => $chap->id,
                        'name' => $chap->name,
                        'slug' => $chap->slug,
                        'count' => $chapCount
                    ];
                })->filter(function($chap) {
                    return $chap['count'] > 0;
                })->values()->toArray();
                
            return [
                'id' => $jc->id,
                'name' => $jc->name,
                'slug' => $jc->slug,
                'count' => $count,
                'class_id' => $jc->category_id,
                'chapters' => $chapters
            ];
        })->filter(function($jc) {
            return $jc['count'] > 0;
        })->values()->toArray();
        
        return [
            'total_questions' => $totalQuestions,
            'mcq_count' => $mcqCount,
            'cq_count' => $cqCount,
            'chapters_count' => $chaptersCount ?: 74,
            'classes_count' => $classesCount ?: 3,
            'classes' => $classesSidebar,
            'subjects' => $subjects,
            'types' => [
                ['key' => 'mcq', 'name' => 'বহুনির্বাচনী', 'count' => $mcqCount],
                ['key' => 'cq', 'name' => 'সৃজনশীল', 'count' => $cqCount],
                ['key' => 'short', 'name' => 'সংক্ষিপ্ত', 'count' => $shortCount],
            ]
        ];
    }

    private function findModelBySlug($modelClass, $slug)
    {
        $slug = trim($slug, '/');
        $slugVariants = [
            $slug,
            str_replace('-', ' ', $slug),
            str_replace(' ', '-', $slug),
            urldecode($slug),
            str_replace('-', ' ', urldecode($slug)),
            str_replace(' ', '-', urldecode($slug)),
        ];

        // Try exact matches first
        $item = $modelClass::whereIn('slug', $slugVariants)->where('status', 1)->first();
        if ($item) {
            return $item;
        }

        // Try normalized match (remove spaces, hyphens, URL encodings)
        $cleanSlug = str_replace(['-', ' ', '%20'], '', $slug);
        $cleanSlugDecoded = str_replace(['-', ' ', '%20'], '', urldecode($slug));

        $item = $modelClass::where(function($q) use ($cleanSlug, $cleanSlugDecoded) {
            $q->whereRaw("REPLACE(REPLACE(REPLACE(slug, '-', ''), ' ', ''), '%20', '') = ?", [$cleanSlug]);
            if ($cleanSlug !== $cleanSlugDecoded) {
                $q->orWhereRaw("REPLACE(REPLACE(REPLACE(slug, '-', ''), ' ', ''), '%20', '') = ?", [$cleanSlugDecoded]);
            }
        })->where('status', 1)->first();

        return $item;
    }

    private function getCurrentAffairsStats($category)
    {
        // Root is Category 312
        $mainCats = Category::where('parent_id', 312)->where('status', 1)->get();
        $mainCatIds = $mainCats->pluck('id')->toArray();
        
        $subCats = Category::whereIn('parent_id', $mainCatIds)->where('status', 1)->get();
        $subCatIds = $subCats->pluck('id')->toArray();
        
        $descendantIds = array_unique(array_merge([312], $mainCatIds, $subCatIds));
        
        $mcqCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'mcq')->count();
        $cqCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::whereIn('category_id', $descendantIds)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
        $shortCount = Question::whereIn('category_id', $descendantIds)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
        $totalQuestions = $mcqCount + $cqCount + $shortCount;
        
        $subCatsCount = $subCats->count();
        $mainCatsCount = $mainCats->count();
        
        // Main categories list for sidebar
        $classesSidebar = $mainCats->map(function($c) {
            $catSubIds = [$c->id];
            $subs = Category::where('parent_id', $c->id)->pluck('id')->toArray();
            $catSubIds = array_merge($catSubIds, $subs);
            $mcqCount = Question::whereIn('category_id', $catSubIds)->where('status', 1)->where('question_type', 'mcq')->count();
            $cqCount = Question::whereIn('category_id', $catSubIds)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::whereIn('category_id', $catSubIds)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
            $shortCount = Question::whereIn('category_id', $catSubIds)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
            $count = $mcqCount + $cqCount + $shortCount;
            return [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'count' => $count
            ];
        })->filter(function($c) {
            return $c['count'] > 0;
        })->values()->toArray();
        
        // Subjects list for sidebar (Job Categories)
        $subjects = JobCategory::whereIn('category_id', array_merge([312], $mainCatIds, $subCatIds))->where('status', 1)->get()->map(function($jc) use ($mainCatIds) {
            $mcqCount = Question::where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'mcq')->count();
            $cqCount = Question::where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
            $shortCount = Question::where('job_category_id', $jc->id)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
            $count = $mcqCount + $cqCount + $shortCount;
            
            // Find which main categories have questions in this job category
            $questionCatIds = Question::where('job_category_id', $jc->id)
                ->where('status', 1)
                ->select('category_id')
                ->distinct()
                ->pluck('category_id')
                ->toArray();
            
            // Find which main categories (or their children) these question categories belong to
            $associatedMainCatIds = [];
            foreach ($questionCatIds as $qCatId) {
                if (in_array($qCatId, $mainCatIds)) {
                    $associatedMainCatIds[] = $qCatId;
                } else {
                    $parentCat = Category::find($qCatId);
                    if ($parentCat && in_array($parentCat->parent_id, $mainCatIds)) {
                        $associatedMainCatIds[] = $parentCat->parent_id;
                    }
                }
            }
            $associatedMainCatIds = array_unique($associatedMainCatIds);
            
            // If no questions yet, default to its own category_id if it's one of the main categories
            if (empty($associatedMainCatIds)) {
                if (in_array($jc->category_id, $mainCatIds)) {
                    $associatedMainCatIds[] = $jc->category_id;
                } elseif ($jc->category_id == 312) {
                    $associatedMainCatIds = $mainCatIds; // Show for all main categories if it's root
                } else {
                    $parentCat = Category::find($jc->category_id);
                    if ($parentCat && in_array($parentCat->parent_id, $mainCatIds)) {
                        $associatedMainCatIds[] = $parentCat->parent_id;
                    }
                }
            }
            
            // Find sub-categories (chapters) for this job category
            $subCatIdsForJc = Question::where('job_category_id', $jc->id)
                ->where('status', 1)
                ->select('category_id')
                ->distinct()
                ->pluck('category_id')
                ->toArray();
                
            $chapters = Category::whereIn('id', $subCatIdsForJc)
                ->whereNotNull('parent_id')
                ->where('parent_id', '!=', 312)
                ->where('status', 1)
                ->get()
                ->map(function($sub) use ($jc) {
                    $subMcqCount = Question::where('category_id', $sub->id)->where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'mcq')->count();
                    $subCqCount = Question::where('category_id', $sub->id)->where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->distinct('passage_id')->count('passage_id') + Question::where('category_id', $sub->id)->where('job_category_id', $jc->id)->where('status', 1)->where('question_type', 'cq')->whereNull('passage_id')->count();
                    $subShortCount = Question::where('category_id', $sub->id)->where('job_category_id', $jc->id)->where('status', 1)->whereIn('question_type', ['short', 'short_answer'])->count();
                    $subCount = $subMcqCount + $subCqCount + $subShortCount;
                    return [
                        'id' => $sub->id,
                        'name' => $sub->name,
                        'slug' => $sub->slug,
                        'count' => $subCount
                    ];
                })->filter(function($sub) {
                    return $sub['count'] > 0;
                })->values()->toArray();
                
            return [
                'id' => $jc->id,
                'name' => $jc->name,
                'slug' => $jc->slug,
                'count' => $count,
                'class_ids' => $associatedMainCatIds,
                'chapters' => $chapters
            ];
        })->filter(function($jc) {
            return $jc['count'] > 0;
        })->values()->toArray();
        
        return [
            'total_questions' => $totalQuestions,
            'mcq_count' => $mcqCount,
            'cq_count' => $cqCount,
            'short_count' => $shortCount,
            'chapters_count' => $subCatsCount ?: 74,
            'classes_count' => $mainCatsCount ?: 2,
            'classes' => $classesSidebar,
            'subjects' => $subjects,
            'types' => [
                ['key' => 'mcq', 'name' => 'বহুনির্বাচনী', 'count' => $mcqCount],
                ['key' => 'cq', 'name' => 'সৃজনশীল', 'count' => $cqCount],
                ['key' => 'short', 'name' => 'সংক্ষিপ্ত', 'count' => $shortCount],
            ]
        ];
    }

    public function currentAffairsFilter(Request $request)
    {
        $caId = 312;
        
        // Enforce that class_ids, subject_ids, and types are all present and non-empty
        if (!$request->has('class_ids') || !is_array($request->class_ids) || empty($request->class_ids) ||
            !$request->has('subject_ids') || !is_array($request->subject_ids) || empty($request->subject_ids) ||
            !$request->has('types') || !is_array($request->types) || empty($request->types)) {
            
            return response()->json([
                'questions' => [],
                'pagination' => [
                    'total' => 0,
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 20,
                ]
            ]);
        }
        
        // Get all category IDs under Current Affairs hierarchy
        $caCategoryIds = [$caId];
        $level1 = Category::where('parent_id', $caId)->pluck('id')->toArray();
        $caCategoryIds = array_merge($caCategoryIds, $level1);
        if (!empty($level1)) {
            $level2 = Category::whereIn('parent_id', $level1)->pluck('id')->toArray();
            $caCategoryIds = array_merge($caCategoryIds, $level2);
        }
        $caCategoryIds = array_unique($caCategoryIds);
        
        // Start Query
        $query = Question::with(['options', 'category', 'job_category', 'year'])
            ->whereIn('category_id', $caCategoryIds)
            ->where('status', 1)
            ->where(function($q) {
                $q->where('question_type', '!=', 'cq')
                  ->orWhereNull('passage_id')
                  ->orWhereIn('id', function($subQuery) {
                      $subQuery->selectRaw('MIN(id)')
                               ->from('questions')
                               ->where('question_type', 'cq')
                               ->whereNotNull('passage_id')
                               ->groupBy('passage_id');
                  });
            });
            
        // Filter by Chapters (Categories)
        if ($request->has('chapter_ids') && is_array($request->chapter_ids) && !empty($request->chapter_ids)) {
            $query->whereIn('category_id', $request->chapter_ids);
        } else {
            // Filter by Classes
            if ($request->has('class_ids') && is_array($request->class_ids) && !empty($request->class_ids)) {
                // Find all subcategories (chapters/subjects) recursively under the selected class category IDs (up to 3 levels deep)
                $classCategoryIds = $request->class_ids;
                $allCategoryIds = $classCategoryIds;
                
                $subLevel1 = Category::whereIn('parent_id', $classCategoryIds)->pluck('id')->toArray();
                if (!empty($subLevel1)) {
                    $allCategoryIds = array_merge($allCategoryIds, $subLevel1);
                    $subLevel2 = Category::whereIn('parent_id', $subLevel1)->pluck('id')->toArray();
                    if (!empty($subLevel2)) {
                        $allCategoryIds = array_merge($allCategoryIds, $subLevel2);
                    }
                }
                $targetCategoryIds = array_unique($allCategoryIds);
                
                $query->whereIn('category_id', $targetCategoryIds);
            }
        }
        
        // Filter by Subjects (Job Category)
        if ($request->has('subject_ids') && is_array($request->subject_ids) && !empty($request->subject_ids)) {
            $query->whereIn('job_category_id', $request->subject_ids);
        }
        
        // Filter by Search Query
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhereHas('category', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('job_category', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Calculate dynamic stats before filtering by question types
        $statsQuery = clone $query;
        $mcqCount = (clone $statsQuery)->where('question_type', 'mcq')->count();
        $cqCount = (clone $statsQuery)->where('question_type', 'cq')->count();
        $shortCount = (clone $statsQuery)->whereIn('question_type', ['short', 'short_answer'])->count();
        $totalQuestions = $mcqCount + $cqCount + $shortCount;
        
        $activeCategoryIds = (clone $statsQuery)->distinct()->pluck('category_id')->toArray();
        $chaptersCount = Category::whereIn('id', $activeCategoryIds)
            ->whereNotNull('parent_id')
            ->where('parent_id', '!=', $caId)
            ->where('status', 1)
            ->count();
            
        $activeSubjectIds = (clone $statsQuery)->whereNotNull('job_category_id')->distinct()->pluck('job_category_id')->toArray();
        $subjectsCount = count($activeSubjectIds);
        
        $classesCount = 0;
        if (!empty($activeCategoryIds)) {
            $parentIds = Category::whereIn('id', $activeCategoryIds)->pluck('parent_id')->filter()->unique()->toArray();
            $classesCount = Category::where(function($q) use ($activeCategoryIds, $parentIds) {
                $q->whereIn('id', $activeCategoryIds)
                  ->orWhereIn('id', $parentIds);
            })->where('parent_id', $caId)->where('status', 1)->count();
        }

        // Now filter by Types for the actual questions list
        if ($request->has('types') && is_array($request->types) && !empty($request->types)) {
            $mappedTypes = [];
            foreach ($request->types as $type) {
                if ($type === 'short') {
                    $mappedTypes[] = 'short_answer';
                    $mappedTypes[] = 'short';
                } else {
                    $mappedTypes[] = $type;
                }
            }
            $query->whereIn('question_type', $mappedTypes);
        }
        
        // Execute and Paginate
        if ($request->has('print_all') && $request->print_all == 1) {
            $questions = $query->orderBy('id', 'desc')->paginate(1000);
        } else {
            $questions = $query->orderBy('id', 'desc')->paginate(20);
        }
        
        // Format Questions for view
        $formatted = collect($questions->items())->map(function($q) {
            $opt = $q->options;
            return [
                'id' => $q->id,
                'question' => $q->question,
                'question_type' => $q->question_type,
                'question_type_name' => $q->question_type === 'mcq' ? 'বহুনির্বাচনী' : ($q->question_type === 'cq' ? 'সৃজনশীল' : 'সংক্ষিপ্ত'),
                'hard_level' => $q->hard_level ?? 'easy',
                'hard_level_name' => $q->hard_level === 'medium' ? 'মাঝারি' : ($q->hard_level === 'hard' ? 'কঠিন' : 'সহজ'),
                'category_name' => $q->category?->name,
                'job_category_name' => $q->job_category?->name,
                'year_name' => $q->year?->name ? 'বোর্ড ' . $q->year->name : '',
                'correct_answer' => $q->correct_answer,
                'options' => $opt ? [
                    'option_a' => html_entity_decode($opt->option_one),
                    'option_b' => html_entity_decode($opt->option_two),
                    'option_c' => html_entity_decode($opt->option_three),
                    'option_d' => html_entity_decode($opt->option_four),
                    'option_e' => html_entity_decode($opt->option_five),
                ] : null,
                'edit_url' => route('portal.question.edit', $q->id),
                'view' => $q->view ?: 0
            ];
        });
        
        // Return JSON response
        return response()->json([
            'questions' => $formatted,
            'pagination' => [
                'total' => $questions->total(),
                'current_page' => $questions->currentPage(),
                'last_page' => $questions->lastPage(),
                'per_page' => $questions->perPage(),
            ],
            'stats' => [
                'total_questions' => $totalQuestions,
                'mcq_count' => $mcqCount,
                'cq_count' => $cqCount,
                'short_count' => $shortCount,
                'chapters_count' => $chaptersCount,
                'subjects_count' => $subjectsCount,
                'classes_count' => $classesCount
            ]
        ]);
    }
}
