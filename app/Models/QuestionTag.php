<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_question_tag');
    }
}
