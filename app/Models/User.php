<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use MustVerifyEmail, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'grade',
        'role',
        'newsletter',
        'last_login_at',
        'zip_code',
        'city',
        'state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'newsletter' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the display name for the user's grade.
     */
    public function getGradeDisplayAttribute(): string
    {
        return match($this->grade) {
            'grade_9_10' => 'Grade 9 & 10',
            'grade_11' => 'Grade 11',
            'grade_12' => 'Grade 12',
            default => 'Unknown',
        };
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Admins are always considered verified so the 'verified' middleware
     * never blocks the admin panel.
     */
    public function hasVerifiedEmail(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return ! is_null($this->email_verified_at);
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class)->orderByDesc('logged_in_at');
    }

    public function contactSubmissions(): HasMany
    {
        return $this->hasMany(ContactSubmission::class, 'email', 'email');
    }
}
