<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_admin'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Every tutor gets a public profile code (T-1001) once their id exists.
        static::created(function (User $user) {
            if ($user->role === 'tutor' && ! $user->tutor_code) {
                $user->forceFill(['tutor_code' => 'T-'.(1000 + $user->id)])->saveQuietly();
            }
        });
    }

    // Relationships
    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class);
    }

    public function tuitionOffers()
    {
        return $this->hasMany(TuitionOffer::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'tutor_id');
    }

    // Helpers
    public function isTutor()
    {
        return $this->role === 'tutor';
    }

    public function isParent()
    {
        return $this->role === 'parent';
    }

    /** Admin pages need both: the admin role and the admin flag. */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' && (bool) $this->is_admin;
    }

    public function hasTutorProfile()
    {
        return $this->tutorProfile !== null;
    }

    public function profileUrl(): string
    {
        return route('tutors.show', $this->tutor_code ?? $this->id);
    }
}
