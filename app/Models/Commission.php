<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Commission extends Model
{
    protected $fillable = [
        'match_type',
        'match_id',
        'tutor_id',
        'parent_id',
        'amount',
        'payment_method',
        'receipt_number',
        'paid_at',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the tutor
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    /**
     * Get the parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the match (application or invitation)
     */
    public function match()
    {
        if ($this->match_type === 'application') {
            return Application::find($this->match_id);
        } elseif ($this->match_type === 'invitation') {
            return Invitation::find($this->match_id);
        }
        return null;
    }

    /**
     * Check if commission is paid
     */
    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    /**
     * Mark commission as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'paid_at' => now(),
        ]);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' BDT';
    }
}
