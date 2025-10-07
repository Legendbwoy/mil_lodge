<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'service_number',
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'rank',
        'branch',
        'unit',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function dependents()
    {
        return $this->hasMany(Dependent::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getMilitaryTitleAttribute()
    {
        $rankTitles = [
            'private' => 'PVT',
            'corporal' => 'CPL',
            'sergeant' => 'SGT',
            'lieutenant' => 'LT',
            'captain' => 'CPT',
            'major' => 'MAJ',
            'colonel' => 'COL'
        ];

        $rankTitle = $rankTitles[$this->rank] ?? strtoupper($this->rank);
        return "{$rankTitle} {$this->first_name} {$this->last_name}";
    }

    public function getFormattedServiceNumberAttribute()
    {
        return substr($this->service_number, 0, 3) . '-' . 
               substr($this->service_number, 3, 2) . '-' . 
               substr($this->service_number, 5);
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeByBranch($query, $branch)
    {
        return $query->where('branch', $branch);
    }

    public function scopeByRank($query, $rank)
    {
        return $query->where('rank', $rank);
    }

    // Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function hasUpcomingBookings()
    {
        return $this->bookings()
            ->where('check_in_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }
}