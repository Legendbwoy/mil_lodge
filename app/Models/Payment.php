<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getAmountFormattedAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getPaymentMethodFormattedAttribute()
    {
        $methods = [
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'military_credit' => 'Military Credit',
            'government_charge' => 'Government Charge'
        ];

        return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge badge-warning',
            'completed' => 'badge badge-success',
            'failed' => 'badge badge-danger',
            'refunded' => 'badge badge-info'
        ];

        return $badges[$this->status] ?? 'badge badge-secondary';
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Methods
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now()
        ]);
    }

    public function isSuccessful()
    {
        return $this->status === 'completed';
    }

    public function processRefund()
    {
        if ($this->isSuccessful()) {
            $this->update(['status' => 'refunded']);
            // Process refund logic here
            return true;
        }
        return false;
    }
}