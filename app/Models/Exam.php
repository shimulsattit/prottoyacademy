<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_id',
        'job_category_id',
        'year_id',
        'uuid',
        'name',
        'slug',
        'name_in_bangla',
        'content',
        'site_title',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_article_tags',
        'status'
    ];

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id');
    }
}
