<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuitionOffer extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'area_id',
        'title',
        'description',
        'student_class',
        'subjects',
        'tutor_gender_preference',
        'salary',
        'tuition_type',
        'days_per_week',
        'location',
        'status',
        'posted_at',
    ];

    protected $casts = [
        'subjects' => 'array',
        'salary' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('posted_at', 'desc');
    }

    // Helpers
    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function getApplicationsCount()
    {
        return $this->applications()->count();
    }
}
