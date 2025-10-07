<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Accommodation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'location',
        'price_per_night',
        'max_guests',
        'bedrooms',
        'bathrooms',
        'is_available',
        'is_featured',
        'rating',
        'review_count',
        'images',
        'square_feet',
        'family_friendly',
        'total_beds',
        'available_beds',
        'block_name',
        'lodge_name',
        'type' // Make sure this is included
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'decimal:2',
        'images' => 'array',
        'total_beds' => 'integer',
        'available_beds' => 'integer',
        'family_friendly' => 'boolean',
    ];

    /* Relationships */
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'accommodation_amenities')->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /* Accessors */
    public function getPriceFormattedAttribute()
    {
        return 'GHC'. number_format($this->price_per_night, 2);
    }

    public function getOccupiedBedsAttribute()
    {
        return $this->total_beds - $this->available_beds;
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->total_beds == 0) return 0;
        return ($this->occupied_beds / $this->total_beds) * 100;
    }

    public function getFeaturedImageAttribute()
    {
        if (is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        return 'https://via.placeholder.com/600x400?text=No+Image';
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    /* Scopes */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('available_beds', '>', 0);
    }

    public function scopeByLodge($query, $lodgeName)
    {
        return $query->where('lodge_name', $lodgeName);
    }

    public function scopeByBlock($query, $blockName)
    {
        return $query->where('block_name', $blockName);
    }

    /* Bed Management */
    public function bookBeds($numberOfBeds)
    {
        if ($this->available_beds >= $numberOfBeds) {
            $this->available_beds -= $numberOfBeds;
            return $this->save();
        }
        return false;
    }

    public function releaseBeds($numberOfBeds)
    {
        $this->available_beds += $numberOfBeds;
        if ($this->available_beds > $this->total_beds) {
            $this->available_beds = $this->total_beds;
        }
        return $this->save();
    }

    public function isAvailableForBeds($numberOfBeds, $checkIn, $checkOut)
    {
        if ($this->available_beds < $numberOfBeds) {
            return false;
        }

        // Check for date conflicts
        $conflictingBookings = $this->bookings()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();

        return $conflictingBookings === 0;
    }
}