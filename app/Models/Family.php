<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Family extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard = 'family';

    protected $fillable = [
        'family_name',
        'contact_email',
        'contact_phone',
        'password',
        'city',
        'is_demo',
        'is_active',
        'privacy_level',
        'bio',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_demo' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the name for display based on privacy level
     */
    public function getDisplayNameAttribute(): string
    {
        return match($this->privacy_level) {
            'anonymous' => 'Anonymous Family',
            'first_name' => explode(' ', $this->family_name)[0] . ' Family',
            'full_name' => $this->family_name,
            default => $this->family_name,
        };
    }

    /**
     * Scope for active families
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for demo families
     */
    public function scopeDemo($query)
    {
        return $query->where('is_demo', true);
    }
}