<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students        = Student::latest()->paginate(25);
        $totalStudents   = Student::count();
        $activeStudents  = Student::where('status', 1)->count();
        $blockedStudents = Student::where('status', 0)->count();
        return view('portal.student.index', compact(
            'students', 'totalStudents', 'activeStudents', 'blockedStudents'
        ));
    }

    public function block($id)
    {
        Student::findOrFail($id)->update(['status' => 0]);
        return back()->with('success', 'শিক্ষার্থী ব্লক করা হয়েছে।');
    }

    public function unblock($id)
    {
        Student::findOrFail($id)->update(['status' => 1]);
        return back()->with('success', 'শিক্ষার্থী আনব্লক করা হয়েছে।');
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return back()->with('success', 'শিক্ষার্থী মুছে দেওয়া হয়েছে।');
    }
}
