<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'teacher';

    protected $fillable = [
        'name', 'email', 'password', 'subject', 'bio',
        'avatar', 'status', 'approved_by', 'approved_at', 'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'approved_at' => 'datetime',
        'password'    => 'hashed',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'teacher_id');
    }

    public function pendingQuestions()
    {
        return $this->hasMany(Question::class, 'teacher_id')->where('teacher_status', 'pending');
    }

    public function approvedQuestions()
    {
        return $this->hasMany(Question::class, 'teacher_id')->where('teacher_status', 'approved');
    }

    public function rejectedQuestions()
    {
        return $this->hasMany(Question::class, 'teacher_id')->where('teacher_status', 'rejected');
    }
}
