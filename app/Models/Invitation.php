<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = [
        'parent_id',
        'tutor_id',
        'tuition_offer_id',
        'message',
        'status',
        'tutor_response_message',
        'accepted_at',
        'rejected_at',
        'sent_to_admin_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'sent_to_admin_at' => 'datetime',
    ];

    /**
     * Get the parent (user) who sent the invitation
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the tutor (user) who received the invitation
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /**
     * Get the tuition offer associated with the invitation (optional)
     */
    public function tuitionOffer(): BelongsTo
    {
        return $this->belongsTo(TuitionOffer::class, 'tuition_offer_id');
    }

    /**
     * Check if invitation is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if invitation is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if invitation is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if invitation is with admin
     */
    public function isWithAdmin(): bool
    {
        return in_array($this->status, ['with_admin', 'contacted', 'negotiating', 'commission_paid', 'completed']);
    }

    /**
     * Mark invitation as accepted
     */
    public function markAsAccepted(): void
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    /**
     * Mark invitation as rejected
     */
    public function markAsRejected(): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);
    }

    /**
     * Send to admin queue
     */
    public function sendToAdmin(): void
    {
        $this->update([
            'status' => 'with_admin',
            'sent_to_admin_at' => now(),
        ]);
    }
}
