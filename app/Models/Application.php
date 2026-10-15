<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'tuition_offer_id',
        'tutor_id',
        'cover_letter',
        'status',
        'applied_at',
        'sent_to_admin_at',
        'admin_contacted_at',
        'parent_contact_shared_at',
        'commission_id',
        'admin_notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'sent_to_admin_at' => 'datetime',
        'admin_contacted_at' => 'datetime',
        'parent_contact_shared_at' => 'datetime',
    ];

    // Relationships
    public function tuitionOffer()
    {
        return $this->belongsTo(TuitionOffer::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    // Helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}