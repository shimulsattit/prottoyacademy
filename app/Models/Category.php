<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'parent_id',
        'admin_id',
        'name',
        'slug',
        'name_in_bangla',
        'header',
        'header_in_bangla',
        'content',
        'site_title',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_article_tag',
        'status',
        'is_deleted'
    ];

    public function breadcrumb()
    {
        $category = $this;
        $breadcrumbs = collect([]);

        while ($category) {
            $breadcrumbs->prepend($category);   // add to beginning
            $category = $category->parent;
        }

        return $breadcrumbs;
    }


    public function questions()
    {
        return $this->hasMany(Question::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function job_categories()
    {
        return $this->hasMany(JobCategory::class, 'category_id');
    }

    public function allChildrenIds()
    {
        $ids = collect([$this->id]);

        foreach ($this->children as $child) {
            $ids = $ids->merge($child->allChildrenIds());
        }

        return $ids;
    }

    public function totalQuestionsCount()
    {
        $allCategoryIds = $this->allChildrenIds();
        return Question::whereIn('category_id', $allCategoryIds)->count();
    }

}
