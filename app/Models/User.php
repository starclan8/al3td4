<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_family',
        'family_name',
        'contact_phone',
        'city',
        'privacy_level',
        'bio',
        'is_demo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_family' => 'boolean',
        'is_demo' => 'boolean',
    ];

    /**
     * Get display name based on privacy settings
     */
    public function getDisplayNameAttribute(): string
    {
        if (!$this->is_family) {
            return $this->name;
        }

        return match($this->privacy_level) {
            'anonymous' => 'Anonymous Family',
            'first_name' => explode(' ', $this->family_name ?? $this->name)[0] . ' Family',
            'full_name' => $this->family_name ?? $this->name,
            default => $this->name,
        };
    }

    /**
     * Check if user is a family account
     */
    public function isFamilyAccount(): bool
    {
        return $this->is_family;
    }

    /**
     * Check if user can seek help
     */
    public function canSeekHelp(): bool
    {
        return $this->hasRole('seeker');
    }

    /**
     * Check if user can provide help
     */
    public function canProvideHelp(): bool
    {
        return $this->hasRole('helper');
    }

    /**
     * Check if user is paying it forward (both seeker and helper)
     */
    public function isPayingItForward(): bool
    {
        return $this->hasAllRoles(['seeker', 'helper']);
    }

    /**
     * Scope for family accounts
     */
    public function scopeFamilies($query)
    {
        return $query->where('is_family', true);
    }

    /**
     * Scope for seekers
     */
    public function scopeSeekers($query)
    {
        return $query->role('seeker');
    }

    /**
     * Scope for helpers
     */
    public function scopeHelpers($query)
    {
        return $query->role('helper');
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Needs posted by this user
     */
    public function needs()
    {
        return $this->hasMany(Need::class);
    }

    /**
     * Needs this user has signed up to help with
     */
    public function helpingWith()
    {
        return $this->hasMany(Helper::class);
    }

    /**
     * Active needs this user posted
     */
    public function activeNeeds()
    {
        return $this->hasMany(Need::class)->active();
    }
}