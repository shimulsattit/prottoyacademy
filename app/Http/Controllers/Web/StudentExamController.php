<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentExam as Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\JobCategory;
use Illuminate\Support\Facades\Auth;

class StudentExamController extends Controller
{
    // Show JobCategory as Exam
    public function show($slug)
    {
        $jobCategory = JobCategory::where('slug', $slug)->firstOrFail();
        
        $activeQuestions = $jobCategory->questions()->where('status', 1)->with('category')->get();
        
        $totalQuestions = $activeQuestions->count();
        $totalMarks = $totalQuestions; 
        $duration = $totalQuestions; // 1 min per question
        $subjects = $activeQuestions->pluck('category.name')->unique()->filter()->values();

        return view('web.exam', compact('jobCategory', 'totalQuestions', 'totalMarks', 'duration', 'subjects'));
    }

    // Start Exam (AJAX)
    public function start(Request $request, $id)
    {
        if (!Auth::guard('student')->check()) {
            return response()->json(['error'=>'login_required'], 401);
        }

        // Try to find JobCategory by ID or Slug just in case
        $jobCategory = JobCategory::where('id', $id)->orWhere('slug', $id)->first();
        
        if (!$jobCategory) {
             return response()->json(['error'=>'not_found'], 404);
        }

        $questions = \App\Models\Question::with('options')
            ->where('job_category_id', $jobCategory->id)
            ->where('status', 1)
            ->get();

        \Log::info("Exam Start Attempt: JobCategory ID: {$jobCategory->id}, Questions Found: " . $questions->count());

        return response()->json([
            'jobCategory' => $jobCategory,
            'questions' => $questions->map(function($q){
                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'type' => $q->question_type,
                    'options' => $q->options ? [
                        $q->options->option_one,
                        $q->options->option_two,
                        $q->options->option_three,
                        $q->options->option_four,
                        $q->options->option_five
                    ] : [],
                    'correct_answer' => $q->correct_answer
                ];
            })
        ]);
    }

    // Submit Exam
    public function submit(Request $request, $jobCategoryId)
    {
        if (!Auth::guard('student')->check()) {
            return response()->json(['error'=>'login_required'], 401);
        }

        $jobCategory = JobCategory::with('questions')->findOrFail($jobCategoryId);
        $studentId = Auth::guard('student')->id();

        $attempt = ExamAttempt::create([
            'job_category_id' => $jobCategory->id,
            'student_id' => $studentId,
            'total_questions' => count($request->answers),
        ]);

        $right = $wrong = $noAns = 0;
        $marks = $negMarks = 0;
        $negative_mark = 0.25; // fixed example

        foreach($request->answers as $qId => $opt){
            $question = $jobCategory->questions->where('id',$qId)->first();
            $isCorrect = $question && $question->correct_answer == $opt;

            ExamAnswer::create([
                'exam_attempt_id' => $attempt->id,
                'question_id' => $qId,
                'selected_option' => $opt,
                'is_correct' => $isCorrect
            ]);

            if($opt === null) $noAns++;
            elseif($isCorrect) { $right++; $marks += 1; }
            else { $wrong++; $negMarks += $negative_mark; }
        }

        $attempt->update([
            'answered' => count($request->answers) - $noAns,
            'right_answers' => $right,
            'wrong_answers' => $wrong,
            'no_answers' => $noAns,
            'marks_obtained' => $marks,
            'negative_marks' => $negMarks,
            'passed' => $marks >= 0 // pass_mark logic here
        ]);

        return response()->json(['success'=>true,'attempt'=>$attempt]);
    }
}
