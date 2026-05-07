<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_id',
        'category_id',
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

    public function exams()
    {
        return $this->hasMany(Exam::class, 'job_category_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'job_category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
