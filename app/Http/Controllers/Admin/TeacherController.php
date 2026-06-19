<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Question;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers        = Teacher::latest()->paginate(20);
        $totalTeachers   = Teacher::count();
        $activeTeachers  = Teacher::where('status', 'active')->count();
        $pendingTeachers = Teacher::where('status', 'pending')->count();
        $blockedTeachers = Teacher::where('status', 'blocked')->count();
        return view('portal.teacher.index', compact(
            'teachers', 'totalTeachers', 'activeTeachers', 'pendingTeachers', 'blockedTeachers'
        ));
    }

    public function pending()
    {
        $teachers = Teacher::where('status', 'pending')->latest()->paginate(20);
        return view('portal.teacher.pending', compact('teachers'));
    }

    public function approve($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->update([
            'status'      => 'active',
            'approved_by' => auth()->guard('admin')->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'শিক্ষক অনুমোদিত হয়েছে।');
    }

    public function block($id)
    {
        Teacher::findOrFail($id)->update(['status' => 'blocked']);
        return back()->with('success', 'শিক্ষক ব্লক করা হয়েছে।');
    }

    public function unblock($id)
    {
        Teacher::findOrFail($id)->update(['status' => 'active']);
        return back()->with('success', 'শিক্ষক আনব্লক করা হয়েছে।');
    }

    public function destroy($id)
    {
        Teacher::findOrFail($id)->delete();
        return back()->with('success', 'শিক্ষক মুছে দেওয়া হয়েছে।');
    }

    public function pendingQuestions()
    {
        $questions = Question::whereNotNull('teacher_id')
            ->where('teacher_status', 'pending')
            ->with(['teacher', 'category', 'options'])
            ->latest()->paginate(20);
        return view('portal.teacher.questions', compact('questions'));
    }

    public function approveQuestion($id)
    {
        Question::findOrFail($id)->update([
            'teacher_status' => 'approved',
            'status'         => 1,
            'admin_id'       => auth()->guard('admin')->id(),
        ]);
        return back()->with('success', 'প্রশ্ন অনুমোদিত হয়েছে।');
    }

    public function rejectQuestion(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        Question::findOrFail($id)->update([
            'teacher_status'             => 'rejected',
            'teacher_rejection_reason'   => $request->reason,
        ]);
        return back()->with('success', 'প্রশ্ন রিজেক্ট করা হয়েছে।');
    }
}
