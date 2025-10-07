<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accommodation_id',
        'report_type',
        'title',
        'description',
        'location',
        'priority',
        'images',
        'status',
        'resolved_at',
        'admin_notes'
    ];

    protected $casts = [
        'images' => 'array',
        'resolved_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    // Accessors
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'badge-info',
            'medium' => 'badge-warning',
            'high' => 'badge-danger',
            'urgent' => 'badge-dark'
        ];

        return $badges[$this->priority] ?? 'badge-secondary';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'in_progress' => 'badge-info',
            'resolved' => 'badge-success',
            'cancelled' => 'badge-secondary'
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getReportTypeLabelAttribute()
    {
        $types = [
            'amenity_issue' => 'Amenity Issue',
            'repair' => 'Repair Needed',
            'renovation' => 'Renovation Suggestion'
        ];

        return $types[$this->report_type] ?? $this->report_type;
    }
}