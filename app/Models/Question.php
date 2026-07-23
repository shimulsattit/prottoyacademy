<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',

        'category_id',
        'question_type',
        'hard_level',
        
        'job_category_id',
        'year_id',
        'exam_id',
        
        'passage_id',
        
        'view',
        'uuid',

        'question',
        'slug',
        
        'question_mark',

        'correct_answer',

        'content',

        'site_title',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_article_tag',
        
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function passage()
    {
        return $this->belongsTo(Passage::class, 'passage_id');
    }

    public function options()
    {
        return $this->HasOne(Option::class);
    }

    public function questionTags()
    {
        return $this->belongsToMany(QuestionTag::class, 'question_question_tag');
    }

    /**
     * Accessor to dynamically replace absolute live storage URLs with relative paths.
     */
    public function getQuestionAttribute($value)
    {
        $value = str_replace('&nbsp;', ' ', $value);
        $value = str_replace('https://prottoyacademy.com/storage/', '/storage/', $value);
        
        // Auto-format statement indices (Roman and Bengali numerals) to start on new lines
        $value = preg_replace('/(?:\s+|^|>)(i|ii|iii|iv|v|vi|vii|viii|ix|x|I|II|III|IV|V|VI|VII|VIII|IX|X|১|২|৩|৪|৫)([\.\)])\s+/iu', '<br>$1$2 ', $value);
        
        // Auto-format "নিচের কোনটি সঠিক?" to have spacing before it
        $value = preg_replace('/\s*(নিচের কোনটি সঠিক\s*\??)/u', '<br><br>$1', $value);
        
        return $value;
    }

    /**
     * Accessor to dynamically replace absolute live storage URLs with relative paths.
     */
    public function getContentAttribute($value)
    {
        return str_replace('https://prottoyacademy.com/storage/', '/storage/', $value);
    }

}
