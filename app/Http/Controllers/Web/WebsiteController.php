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
        if ($model == 'Category' && Category::where('slug', $slug)->where('status', 1)->exists()) {

            $category = Category::where('slug', $slug)->first();

            if ($category) {
                $allJobCategories = JobCategory::with('questions')->where('categorY_id', $category->id)->where('status', 1)->get();
                $sortedCategories = $allJobCategories->sortByDesc(function ($sExam) {
                    if (preg_match('/\((\d{2}-\d{2}-\d{4})\)/', $sExam->name, $matches)) {
                        $parts = explode('-', $matches[1]);
                        if (count($parts) === 3) {
                            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                        }
                    }
                    return '0000-00-00';
                });
                
                $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
                $perPage = 100;
                $currentItems = $sortedCategories->slice(($currentPage - 1) * $perPage, $perPage)->values()->all();
                $jobCategories = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $sortedCategories->count(), $perPage, $currentPage, [
                    'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()
                ]);


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
        } elseif ($model == 'Blog' && Blog::where('slug', $slug)->where('status', 1)->exists()) {
            $blog = Blog::where('slug', $slug)->where('status', 1)->first();

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

        } elseif ($model == 'BlogCategory' && BlogCategory::where('slug', $slug)->where('status', 1)->exists()) {
            $category = BlogCategory::where('slug', $slug)->where('status', 1)->first();
            if ($category) {

                $blogs = Blog::where('blog_category_id', $category->id)->where('status', 1)->paginate(15);

                return view('web.blog-category-listing', compact('category', 'blogs'));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }

        } elseif ($model == 'BlogAuthor' && BlogAuthor::where('slug', $slug)->where('status', 1)->exists()) {
            $author = BlogAuthor::where('slug', $slug)->where('status', 1)->first();
            if ($author) {

                $blogs = Blog::where('blog_author_id', $author->id)->where('status', 1)->paginate(15);

                return view('web.blog-author-listing', compact('author', 'blogs'));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }

        } elseif ($model == 'Page' && Page::where('slug', $slug)->where('status', 1)->exists()) {
            $model = Page::where('status', 1)->where('slug', $slug)->first();
            if($model) {
                return view('web.page', compact('model'));
            } else {
                return $this->fetcher($slug, $request, $index + 1);
            }
        } elseif ($model == 'JobCategory' && JobCategory::where('slug', $slug)->where('status', 1)->exists()) {
            $model = JobCategory::where('status', 1)->where('slug', $slug)->first();
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

                // Now build category array with name only for valid ones
                $categories = Category::whereIn('id', $validCategoryIds)
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
}
