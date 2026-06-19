<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Question;
use App\Models\Category;
use App\Models\JobCategory;
use App\Models\Year;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherAuthController extends Controller
{
    public function loginPage()
    {
        if (auth()->guard('teacher')->check()) {
            return redirect()->route('teacher.dashboard');
        }
        return view('web.teacher.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'ইমেইল প্রয়োজন।',
            'password.required' => 'পাসওয়ার্ড প্রয়োজন।',
        ]);

        if (Auth::guard('teacher')->attempt($request->only('email', 'password'), $request->remember)) {
            $teacher = Auth::guard('teacher')->user();

            if ($teacher->status === 'blocked') {
                Auth::guard('teacher')->logout();
                return back()->withErrors(['email' => 'আপনার অ্যাকাউন্ট ব্লক করা হয়েছে। অ্যাডমিনের সাথে যোগাযোগ করুন।']);
            }
            if ($teacher->status === 'pending') {
                Auth::guard('teacher')->logout();
                return back()->withErrors(['email' => 'আপনার অ্যাকাউন্ট এখনো অনুমোদিত হয়নি। অ্যাডমিনের অনুমোদনের জন্য অপেক্ষা করুন।']);
            }

            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors(['email' => 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।']);
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('teacher.login');
    }

    public function dashboard()
    {
        $teacher          = auth()->guard('teacher')->user();
        $totalQuestions   = $teacher->questions()->count();
        $pendingQuestions = $teacher->pendingQuestions()->count();
        $approvedQuestions= $teacher->approvedQuestions()->count();
        $rejectedQuestions= $teacher->rejectedQuestions()->count();
        $recentQuestions  = $teacher->questions()->with('category')->latest()->take(5)->get();

        return view('web.teacher.dashboard', compact(
            'teacher', 'totalQuestions', 'pendingQuestions',
            'approvedQuestions', 'rejectedQuestions', 'recentQuestions'
        ));
    }

    public function questions()
    {
        $teacher   = auth()->guard('teacher')->user();
        $questions = $teacher->questions()->with('category')->latest()->paginate(20);
        return view('web.teacher.questions.index', compact('questions'));
    }

    public function createQuestion()
    {
        $categories    = Category::where('status', 1)->orderBy('name')->get();
        $jobCategories = JobCategory::where('status', 1)->orderBy('name')->get();
        $years         = Year::orderBy('name', 'desc')->get();
        $exams         = Exam::orderBy('name')->get();
        return view('web.teacher.questions.create', compact('categories', 'jobCategories', 'years', 'exams'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question'    => 'required|string',
            'type'        => 'required|in:mcq,short,cq',
            'category_id' => 'required|exists:categories,id',
        ], [
            'question.required'    => 'প্রশ্ন লিখুন।',
            'type.required'        => 'প্রশ্নের ধরন বেছে নিন।',
            'category_id.required' => 'ক্যাটাগরি বেছে নিন।',
        ]);

        $teacher  = auth()->guard('teacher')->user();
        $question = Question::create([
            'question'           => $request->question,
            'type'               => $request->type,
            'category_id'        => $request->category_id,
            'job_category_id'    => $request->job_category_id,
            'year_id'            => $request->year_id,
            'exam_id'            => $request->exam_id,
            'answer_description' => $request->answer_description,
            'status'             => 0,
            'teacher_id'         => $teacher->id,
            'teacher_status'     => 'pending',
            'admin_id'           => null,
        ]);

        if ($request->type === 'mcq' && $request->has('options')) {
            foreach ($request->options as $i => $optionText) {
                if (!empty($optionText)) {
                    $question->options()->create([
                        'option'     => $optionText,
                        'is_correct' => ($request->correct_option == $i) ? 1 : 0,
                    ]);
                }
            }
        }

        return redirect()->route('teacher.questions')
            ->with('success', 'প্রশ্ন সফলভাবে জমা দেওয়া হয়েছে। অ্যাডমিনের অনুমোদনের পর প্রকাশিত হবে।');
    }

    public function profile()
    {
        $teacher = auth()->guard('teacher')->user();
        return view('web.teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = auth()->guard('teacher')->user();
        $request->validate([
            'name'    => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'bio'     => 'nullable|string|max:1000',
            'phone'   => 'nullable|string|max:20',
        ]);

        $data = $request->only('name', 'subject', 'bio', 'phone');

        if ($request->hasFile('avatar')) {
            $path         = $request->file('avatar')->store('teachers/avatars', 'public');
            $data['avatar'] = 'storage/' . $path;
        }

        $teacher->update($data);
        return back()->with('success', 'প্রোফাইল আপডেট হয়েছে।');
    }

    public function registerPage()
    {
        if (auth()->guard('teacher')->check()) {
            return redirect()->route('teacher.dashboard');
        }
        return view('web.teacher.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:teachers,email',
            'password'              => 'required|min:8|confirmed',
            'subject'               => 'required|string|max:255',
        ], [
            'email.unique'          => 'এই ইমেইল দিয়ে আগেই নিবন্ধন হয়েছে।',
            'password.confirmed'    => 'পাসওয়ার্ড মিলছে না।',
            'password.min'          => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
        ]);

        Teacher::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'subject'  => $request->subject,
            'status'   => 'pending',
        ]);

        return redirect()->route('teacher.login')
            ->with('success', 'নিবন্ধন সফল! অ্যাডমিনের অনুমোদনের পর লগইন করতে পারবেন।');
    }
}
