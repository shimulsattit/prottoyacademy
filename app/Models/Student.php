<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'student';  // যদি আপনি multiple guards ব্যবহার করেন

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'mobile',
        'password',
        'profile_photo_path',
        'status',
        'subscription_plan_id',
        'provider',
        'provider_id',
        'email_verified_at',
        'mobile_verified_at',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays (e.g., JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'  => 'datetime',
        'mobile_verified_at' => 'datetime',
        'last_login_at'      => 'datetime',
    ];

    // Example relation: subscription plan
    // public function subscriptionPlan()
    // {
    //     return $this->belongsTo(\App\Models\SubscriptionPlan::class);
    // }

    public function info()
    {
        return $this->belongsTo(StudentInfo::class, 'id', 'student_id');
    }

    // // Example relation: courses
    // public function courses()
    // {
    //     return $this->belongsToMany(\App\Models\Course::class, 'course_student')
    //                 ->withTimestamps();
    // }
}
