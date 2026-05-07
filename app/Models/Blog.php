<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'blog_category_id', 'blog_author_id',
        'title', 'slug', 'short_description', 'content',
        'thumbnail_image', 'banner_image', 'featured', 'status',
        'site_title', 'meta_title', 'meta_keyword', 'meta_description', 'meta_google_schema', 'published_at'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'status' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(BlogAuthor::class, 'blog_author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_blog_tag');
    }

}
