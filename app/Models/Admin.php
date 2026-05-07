<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'surname',
        'first_name',
        'last_name',
        'username',
        'avatar',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function job_categories()
    {
        return $this->hasMany(JobCategory::class, 'admin_id');
    }

    public function years()
    {
        return $this->hasMany(Year::class, 'admin_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'admin_id');
    }
    
    public function passages()
    {
        return $this->hasMany(Passage::class, 'admin_id');
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class, 'admin_id');
    }
}
