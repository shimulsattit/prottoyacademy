<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionDescriptionLog extends Model
{
    protected $fillable = [
        'type',
        'question_id',
        'admin_id',
        'description'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
