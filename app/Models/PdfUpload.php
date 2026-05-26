<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfUpload extends Model
{
    protected $fillable = [
        'admin_id',
        'category_id',
        'title',
        'file_path',
        'original_name',
        'status',
        'extracted_text',
        'generated_questions',
        'questions_generated',
        'questions_saved',
        'error_message',
    ];

    protected $casts = [
        'generated_questions' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'অপেক্ষমান',
            'processing' => 'প্রক্রিয়াধীন',
            'done'       => 'সম্পন্ন',
            'failed'     => 'ব্যর্থ',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'done'       => 'success',
            'failed'     => 'danger',
            default      => 'secondary',
        };
    }
}
