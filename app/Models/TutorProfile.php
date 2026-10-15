<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'gender',
        'date_of_birth',
        'education',
        'experience_years',
        'bio',
        'subjects',
        'classes',
        'hourly_rate',
        'areas',
        'availability',
        'profile_image',
        'is_verified',
        'is_active',
        'tuition_types',
    ];

    protected $casts = [
        'subjects' => 'array',
        'classes' => 'array',
        'areas' => 'array',
        'tuition_types' => 'array',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
        'hourly_rate' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: Check if profile is complete
    public function isComplete()
    {
        return !empty($this->phone) 
            && !empty($this->education)
            && !empty($this->subjects)
            && !empty($this->classes)
            && !empty($this->areas);
    }

    // Get completion percentage
    public function completionPercentage()
    {
        $fields = ['phone', 'gender', 'education', 'bio', 'subjects', 'classes', 'hourly_rate', 'areas'];
        $filled = 0;
        
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filled++;
            }
        }
        
        return round(($filled / count($fields)) * 100);
    }
}
