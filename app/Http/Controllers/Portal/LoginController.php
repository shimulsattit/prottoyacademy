<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Images;
use App\Models\ExamAttempt;
use App\Models\StudentInfo;
use App\Services\AdminService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $guard = $this->adminService->login($request->only('email', 'password'));

        if ($guard) {
            $request->session()->regenerate();
            return response()->json([
                'status' => true,
                'goto' => route('portal.dashboard'),
                'message' => "Login successfully"
            ], 200);
        }

        return response()->json(["The provided credentials do not match our records."], 422);
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'goto' => route('home'),
                'message' => "Logout successful"
            ]);
        }

        return redirect()->route('home');
    }

    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('login');
        }

        return view('web.student.dashboard');
    }

    public function profile()
    {
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('login');
        }

        $info = $student->info;
        if(!$info) {
            $info = new StudentInfo();
            $info->student_id = $student->id;
            $info->save();
        }

        return view('web.student.profile', compact('student', 'info'));
    }

    public function password()
    {
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('login');
        }

        return view('web.student.password');
    }

    public function myAttendedExams()
    {
        $userId = Auth::guard('student')->id();

        $attempts = ExamAttempt::where('student_id', $userId)->get();

        $totalExams = $attempts->count();

        $passed = $attempts->where('result', 'pass')->count();
        $failed = $attempts->where('result', 'fail')->count();

        $totalQuestions = $attempts->sum('total_questions');
        $totalAnswers = $attempts->sum('answered');

        $rightAnswers = $attempts->sum('correct');
        $wrongAnswers = $attempts->sum('wrong');
        $noAnswers = $attempts->sum('not_answered');

        $totalMarks = $attempts->sum('total_marks');
        $obtainMarks = $attempts->sum('obtained_marks');
        $negativeMarks = $attempts->sum('negative_marks');

        return view('web.student.attended-exams', compact(
            'totalExams',
            'passed',
            'failed',
            'totalQuestions',
            'totalAnswers',
            'rightAnswers',
            'wrongAnswers',
            'noAnswers',
            'totalMarks',
            'obtainMarks',
            'negativeMarks'
        ));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('login');
        }

        $info = $student->info;
        if(!$info) {
            $info = new StudentInfo();
            $info->student_id = $student->id;
            $info->save();
        }

        $student->name = $request->input('name');
        $student->username = $request->input('username');
        if($request->hasFile('avatar')) {
            $student->profile_photo_path = Images::upload('avatars', $request->file('avatar'));
        }
        $student->save();

        $info->update([
            'bio' => $request->input('bio'),
            'highest_education' => $request->input('highest_education'),
            'university' => $request->input('university'),
            'major' => $request->input('major'),
            'current_job_title' => $request->input('current_job_title'),
            'current_company' => $request->input('current_company'),
            'years_of_experience' => $request->input('years_of_experience'),
            'address' => $request->input('address'),
            'address_line_2' => $request->input('address_line_2'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'postal_code' => $request->input('postal_code'),
            'country' => $request->input('country'),
            'linkedin_url' => $request->input('linkedin_url'),
            'github_url' => $request->input('github_url'),
            'twitter_url' => $request->input('twitter_url'),
            'facebook_url' => $request->input('facebook_url'),
            'personal_website_url' => $request->input('personal_website_url'),
            'instagram_url' => $request->input('instagram_url'),
            'youtube_url' => $request->input('youtube_url'),
            'show_email' => $request->has('show_email') ? 1 : 0,
            'show_mobile' => $request->has('show_mobile') ? 1 : 0,
            'show_education' => $request->has('show_education') ? 1 : 0,
            'show_professional' => $request->has('show_professional') ? 1 : 0,
            'show_address' => $request->has('show_address') ? 1 : 0,
            'show_social_media' => $request->has('show_social_media') ? 1 : 0,
        ]);

        return response()->json([
            'status' => true,
            'goto' => route('student.profile'),
            'message' => "Profile updated successfully"
        ]);
    }

    public function updatePassword(Request $request)
    {
        $student = Auth::guard('student')->user();
        if (!$student) {
            return redirect()->route('login');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        if (!password_verify($request->input('current_password'), $student->password)) {
            return response()->json([
                'status' => false,
                'message' => "Current password is incorrect"
            ]);
        }

        $student->password = Hash::make($request->input('new_password'));
        $student->save();

        return response()->json([
            'status' => true,
            'goto' => route('student.password'),
            'message' => "Password updated successfully"
        ]);
    }

}
