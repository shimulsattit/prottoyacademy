<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_one',
        'option_two',
        'option_three',
        'option_four',
        'option_five',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
