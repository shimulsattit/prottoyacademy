<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_id',
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
        return $this->hasMany(Exam::class, 'year_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'year_id');
    }
}
