<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;

    protected $fillable = [
        'need_id',
        'user_id',
        'status',
        'message',
        'confirmed_at',
        'completed_at',
        'completion_notes',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * The need being helped
     */
    public function need()
    {
        return $this->belongsTo(Need::class);
    }

    /**
     * The user helping
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if helper is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if help is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Scopes
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}