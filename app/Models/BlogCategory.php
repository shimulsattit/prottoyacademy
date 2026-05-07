<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'status', 'content',
        'site_title', 'meta_title', 'meta_keyword', 'meta_description', 'meta_google_schema'
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

}
