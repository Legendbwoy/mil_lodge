@extends('layouts.client')

@section('title', 'Accommodation Details - AKAFIA Lodge')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Accommodations
                </a>
            </div>

            @if($accommodation)
            <div class="row">
                <!-- Accommodation Images -->
                <div class="col-lg-8">
                    <div class="card">
                        <img src="{{ $accommodation->featured_image }}" class="card-img-top" alt="{{ $accommodation->name }}">
                        <div class="card-body">
                            <h2 class="card-title">{{ $accommodation->name }}</h2>
                            <p class="text-muted">
                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $accommodation->location }}
                            </p>
                            <p class="card-text">{{ $accommodation->description }}</p>
                            
                            <!-- Accommodation Features -->
                            <div class="row mt-4">
                                <div class="col-md-3 text-center">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                    <p class="mt-2 mb-0">Max Guests</p>
                                    <h5 class="text-primary">{{ $accommodation->max_guests }}</h5>
                                </div>
                                <div class="col-md-3 text-center">
                                    <i class="fas fa-bed fa-2x text-primary"></i>
                                    <p class="mt-2 mb-0">Bedrooms</p>
                                    <h5 class="text-primary">{{ $accommodation->bedrooms }}</h5>
                                </div>
                                <div class="col-md-3 text-center">
                                    <i class="fas fa-bath fa-2x text-primary"></i>
                                    <p class="mt-2 mb-0">Bathrooms</p>
                                    <h5 class="text-primary">{{ $accommodation->bathrooms }}</h5>
                                </div>
                                <div class="col-md-3 text-center">
                                    <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                                    <p class="mt-2 mb-0">Price/Night</p>
                                    <h5 class="text-primary">{{ $accommodation->price_formatted }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Section -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Book This Accommodation</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="accommodation_id" value="{{ $accommodation->id }}">
                                
                                <div class="form-group">
                                    <label for="check_in_date">Check-in Date</label>
                                    <input type="date" class="form-control" id="check_in_date" name="check_in_date" required min="{{ date('Y-m-d') }}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="check_out_date">Check-out Date</label>
                                    <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="number_of_guests">Number of Guests</label>
                                    <select class="form-control" id="number_of_guests" name="number_of_guests" required>
                                        @for($i = 1; $i <= $accommodation->max_guests; $i++)
                                            <option value="{{ $i }}">{{ $i }} Guest{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="guest_name">Your Name</label>
                                    <input type="text" class="form-control" id="guest_name" name="guest_name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="guest_email">Email</label>
                                    <input type="email" class="form-control" id="guest_email" name="guest_email" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="guest_phone">Phone Number</label>
                                    <input type="tel" class="form-control" id="guest_phone" name="guest_phone" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="special_requests">Purpose of Booking</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="What's your purpose for booking this accommodation?"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-calendar-check mr-2"></i> Book Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amenities Section -->
            @if($accommodation->amenities->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Amenities</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($accommodation->amenities as $amenity)
                        <div class="col-md-3 mb-2">
                            <i class="fas fa-check text-success mr-2"></i> {{ $amenity->name }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @else
            <div class="alert alert-danger">
                <h4>Accommodation Not Found</h4>
                <p>The accommodation you're looking for doesn't exist or has been removed.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Back to Accommodations</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    
    if (checkInInput && checkOutInput) {
        checkInInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                if (!checkOutInput.value || new Date(checkOutInput.value) <= new Date(this.value)) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
            }
        });
    }
});
</script>
@endsection