<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogAuthor extends Model
{
    protected $fillable = [
        'name', 'slug', 'email', 'designation', 'content',
        'profile_picture', 'bio', 'meta_title', 'meta_description', 'meta_keyword', 'status'
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

}
