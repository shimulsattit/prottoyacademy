<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_id',
        'uuid',
        'name',
        'passage',
        'status'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'passage_id');
    }
}
