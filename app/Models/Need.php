<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Need extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'needed_by',
        'location',
        'urgency',
        'helper_slots',
        'helpers_signed_up',
        'is_recurring',
        'recurrence_pattern',
        'is_public',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'needed_by' => 'date',
        'approved_at' => 'datetime',
        'is_recurring' => 'boolean',
        'is_public' => 'boolean',
        'helper_slots' => 'integer',
        'helpers_signed_up' => 'integer',
    ];

    /**
     * The user who posted the need
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The admin who approved the need
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Need types (categories)
     */
    public function needTypes()
    {
        return $this->belongsToMany(NeedType::class);
    }

    /**
     * Helpers who signed up
     */
    public function helpers()
    {
        return $this->hasMany(Helper::class);
    }

    /**
     * Confirmed helpers
     */
    public function confirmedHelpers()
    {
        return $this->hasMany(Helper::class)->where('status', 'confirmed');
    }

    /**
     * Check if need is full
     */
    public function isFull(): bool
    {
        return $this->helpers_signed_up >= $this->helper_slots;
    }

    /**
     * Check if need is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' || $this->status === 'active';
    }

    /**
     * Check if need is fulfilled
     */
    public function isFulfilled(): bool
    {
        return $this->status === 'fulfilled';
    }

    /**
     * Check if user can help with this need
     */
    public function canBeHelpedBy(User $user): bool
    {
        // Can't help your own need
        if ($this->user_id === $user->id) {
            return false;
        }

        // Must have helper role
        if (!$user->hasRole('helper')) {
            return false;
        }

        // Need must be active
        if (!$this->isApproved()) {
            return false;
        }

        // Need must not be full
        if ($this->isFull()) {
            return false;
        }

        // User hasn't already signed up
        if ($this->helpers()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeUrgent($query)
    {
        return $query->where('urgency', 'high');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('needed_by', '>=', now());
    }
}