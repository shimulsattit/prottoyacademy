<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedCategory extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function banners()
    {
        return $this->hasMany(FeaturedCategoryBanner::class, 'category_id');
    }
}
