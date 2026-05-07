<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = [
        'job_category_id', 'name', 'description', 'subject', 'time_minutes',
        'pass_mark', 'negative_mark', 'status', 'exam_date'
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')->withPivot('marks', 'negative_mark');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

}
