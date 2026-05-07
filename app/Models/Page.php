<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'parent_id',
        'show_on_navbar',
        'slug',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'meta_article_tag',
        'meta_script_tag',
        'meta_image',
    ];

    // Each page can have one parent
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    // Each page can have multiple children
    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')->where('status', 1)->where('show_on_navbar', 1);
    }
}
