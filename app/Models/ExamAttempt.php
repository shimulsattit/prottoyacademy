<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'job_category_id', 'student_id', 'total_questions', 'answered', 'right_answers',
        'wrong_answers', 'no_answers', 'marks_obtained', 'negative_marks', 'passed'
    ];

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
