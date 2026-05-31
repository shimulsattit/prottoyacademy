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

    public function getOptionOneAttribute($value)
    {
        return str_replace('https://prottoyacademy.com/storage/', '/storage/', $value);
    }

    public function getOptionTwoAttribute($value)
    {
        return str_replace('https://prottoyacademy.com/storage/', '/storage/', $value);
    }

    public function getOptionThreeAttribute($value)
    {
        return str_replace('https://prottoyacademy.com/storage/', '/storage/', $value);
    }

    public function getOptionFourAttribute($value)
    {
        return str_replace('https://prottoyacademy.com/storage/', '/storage/', $value);
    }

    public function getOptionFiveAttribute($value)
    {
        return str_replace('https://prottoyacademy.com/storage/', '/storage/', $value);
    }
}
