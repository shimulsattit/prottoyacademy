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
        
        // Retrieve Category objects to access ID and Name for subject selection
        $subjects = $activeQuestions->pluck('category')->unique('id')->filter()->values();

        return view('web.exam', compact('jobCategory', 'totalQuestions', 'totalMarks', 'duration', 'subjects'));
    }

    // Start Exam (AJAX)
    public function start(Request $request, $id)
    {
        if (!auth()->guard('student')->check()) {
            return response()->json(['error'=>'login_required'], 401);
        }

        // Try to find JobCategory by ID or Slug just in case
        $jobCategory = JobCategory::where('id', $id)->orWhere('slug', $id)->first();
        
        if (!$jobCategory) {
             return response()->json(['error'=>'not_found'], 404);
        }

        $query = \App\Models\Question::with('options')
            ->where('job_category_id', $jobCategory->id)
            ->where('status', 1);

        // Subject filter
        if ($request->filled('subject_id') && $request->subject_id !== 'all') {
            $query->where('category_id', $request->subject_id);
        }

        // Randomize questions for a dynamic practice experience
        $query->inRandomOrder();

        // Limit the number of questions
        if ($request->filled('limit') && intval($request->limit) > 0) {
            $query->limit(intval($request->limit));
        }

        $questions = $query->get();

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
        if (!auth()->guard('student')->check()) {
            return response()->json(['error'=>'login_required'], 401);
        }

        $jobCategory = JobCategory::with('questions')->findOrFail($jobCategoryId);
        $studentId = auth()->guard('student')->id();

        // Retrieve the presented question IDs
        $questionIds = $request->input('question_ids', []);
        if (empty($questionIds)) {
            $questionIds = $jobCategory->questions()->where('status', 1)->pluck('id')->toArray();
        }

        $totalQuestions = count($questionIds);

        $attempt = ExamAttempt::create([
            'job_category_id' => $jobCategory->id,
            'student_id' => $studentId,
            'total_questions' => $totalQuestions,
        ]);

        $right = $wrong = $noAns = 0;
        $marks = $negMarks = 0;
        $negative_mark = floatval($request->input('negative_mark', 0.25));

        foreach($questionIds as $qId){
            $question = $jobCategory->questions->where('id', $qId)->first();
            if (!$question) continue;

            $opt = isset($request->answers[$qId]) ? $request->answers[$qId] : null;
            $isCorrect = ($opt !== null) && ($question->correct_answer == $opt);

            ExamAnswer::create([
                'exam_attempt_id' => $attempt->id,
                'question_id' => $qId,
                'selected_option' => $opt,
                'is_correct' => $isCorrect
            ]);

            if ($opt === null) {
                $noAns++;
            } elseif ($isCorrect) {
                $right++;
                $marks += 1;
            } else {
                $wrong++;
                $negMarks += $negative_mark;
            }
        }

        $finalObtained = $marks - $negMarks;
        
        // Pass mark logic
        $pass_mark = floatval($request->input('pass_mark', 0));
        $isPassed = $finalObtained >= $pass_mark;

        $attempt->update([
            'answered' => $totalQuestions - $noAns,
            'right_answers' => $right,
            'wrong_answers' => $wrong,
            'no_answers' => $noAns,
            'marks_obtained' => $finalObtained,
            'negative_marks' => $negMarks,
            'passed' => $isPassed
        ]);

        return response()->json(['success'=>true,'attempt'=>$attempt]);
    }
}
