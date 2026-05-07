<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedCategoryBanner extends Model
{
    protected $guarded = [];
    
    public function category()
    {
        return $this->belongsTo(FeaturedCategory::class);
    }
}
