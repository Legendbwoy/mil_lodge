@extends('layouts.admin')

@section('title', 'Akafia | Book Accommodation')
@section('page-title', 'Book Your Stay')

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Booking Progress Bar -->
            <div class="card mb-4">
                <div class="card-body px-4">
                    <div class="booking-progress">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                                 id="progressBar" style="width: 25%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div class="text-center">
                                <div class="progress-step bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 35px; height: 35px;">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="text-primary fw-bold small">Select Room</div>
                            </div>
                            <div class="text-center">
                                <div class="progress-step bg-light border text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 35px; height: 35px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="text-muted small">Booking Details</div>
                            </div>
                            <div class="text-center">
                                <div class="progress-step bg-light border text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 35px; height: 35px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="text-muted small">Confirmation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 1: Accommodation Selection -->
            <div class="card" id="step1Accommodation">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-home me-2"></i>
                                Select Your Accommodation
                            </h4>
                            <p class="mb-0 opacity-75 small">Choose from available rooms to proceed with booking</p>
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- System Alerts Container -->
                    <div id="systemAlerts"></div>

                    @if($accommodations->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Accommodations Available</h5>
                            <p class="text-muted mb-4">There are currently no available rooms for booking.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Return Home
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($accommodations as $accommodation)
                                @php
                                    $images = [];
                                    if (is_array($accommodation->images)) {
                                        $images = $accommodation->images;
                                    } else {
                                        $decoded = json_decode($accommodation->images, true);
                                        if (is_array($decoded)) {
                                            $images = $decoded;
                                        }
                                    }
                                    $featuredUrl = count($images) ? $images[0] : asset('images/default-accommodation.jpg');
                                    $availabilityClass = $accommodation->available_beds > 0 ? 'border-success' : 'border-danger';
                                    $availabilityText = $accommodation->available_beds > 0 ? 'text-success' : 'text-danger';
                                    $availabilityIcon = $accommodation->available_beds > 0 ? 'fa-check-circle' : 'fa-times-circle';
                                @endphp
                                
                                <div class="col-md-6 col-lg-4">
                                    <div class="card accommodation-card h-100" 
                                         data-accommodation='@json($accommodation)'
                                         data-featured-image="{{ $featuredUrl }}">
                                        <div class="position-relative">
                                            <img src="{{ $featuredUrl }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $accommodation->name }}"
                                                 style="height: 200px; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge {{ $availabilityClass }} bg-white border border-2 fs-6">
                                                    <i class="fas {{ $availabilityIcon }} {{ $availabilityText }} me-1"></i>
                                                    {{ $accommodation->available_beds }}/{{ $accommodation->total_beds }} beds
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">{{ $accommodation->name }}</h5>
                                            <p class="card-text text-muted mb-2">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                {{ $accommodation->location }}
                                            </p>
                                            
                                            <div class="accommodation-features mb-3">
                                                <div class="row text-center g-2">
                                                    <div class="col-4">
                                                        <div class="border rounded p-2 bg-light">
                                                            <i class="fas fa-bed text-primary mb-1"></i>
                                                            <div class="small fw-bold">{{ $accommodation->bedrooms }}</div>
                                                            <small class="text-muted">Beds</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="border rounded p-2 bg-light">
                                                            <i class="fas fa-bath text-primary mb-1"></i>
                                                            <div class="small fw-bold">{{ $accommodation->bathrooms }}</div>
                                                            <small class="text-muted">Baths</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="border rounded p-2 bg-light">
                                                            <i class="fas fa-users text-primary mb-1"></i>
                                                            <div class="small fw-bold">{{ $accommodation->max_guests }}</div>
                                                            <small class="text-muted">Guests</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="card-text small text-muted">
                                                {{ Str::limit($accommodation->description, 100) }}
                                            </p>

                                            @if($accommodation->available_beds > 0)
                                                <button class="btn btn-primary w-100 select-accommodation-btn" 
                                                        data-id="{{ $accommodation->id }}">
                                                    <i class="fas fa-check me-2"></i>
                                                    Select Room
                                                </button>
                                            @else
                                                <button class="btn btn-outline-secondary w-100" disabled>
                                                    <i class="fas fa-times me-2"></i>
                                                    No Beds Available
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Step 2: Booking Form (Initially Hidden) -->
            <div class="card d-none" id="step2BookingForm">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Complete Your Booking
                            </h4>
                            <p class="mb-0 opacity-75 small" id="selectedRoomTitle">Selected Room: <span id="selectedRoomName"></span></p>
                        </div>
                        <button type="button" class="btn btn-light btn-sm" id="changeRoomBtn">
                            <i class="fas fa-sync me-2"></i> Change Room
                        </button>
                    </div>
                </div>

                <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="accommodation_id" id="accommodationId">
                    <input type="hidden" name="accommodation_name" id="accommodationName">

                    <div class="card-body p-4">
                        <!-- Selected Room Summary -->
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white py-2">
                                <h6 class="mb-0">
                                    <i class="fas fa-home me-2"></i>
                                    Selected Room Details
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img id="selectedRoomImage" src="" alt="Room Image" 
                                             class="img-fluid rounded shadow-sm" style="height: 80px; width: 100%; object-fit: cover;">
                                    </div>
                                    <div class="col-md-6">
                                        <h6 id="selectedRoomFullName" class="text-success mb-1"></h6>
                                        <p class="text-muted mb-1 small">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <span id="selectedRoomLocation"></span>
                                        </p>
                                        <div class="d-flex flex-wrap gap-3 text-muted small">
                                            <span><i class="fas fa-bed me-1"></i> <span id="selectedRoomBeds">0</span> beds</span>
                                            <span><i class="fas fa-bath me-1"></i> <span id="selectedRoomBaths">0</span> baths</span>
                                            <span><i class="fas fa-user me-1"></i> <span id="selectedRoomGuests">0</span> max guests</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border rounded p-2 bg-light">
                                                    <h5 class="mb-0 text-success" id="selectedAvailableBeds">0</h5>
                                                    <small class="text-muted">Available</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2 bg-light">
                                                    <h5 class="mb-0 text-info" id="selectedTotalBeds">0</h5>
                                                    <small class="text-muted">Total</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Military Information Section -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 text-primary d-flex align-items-center">
                                            <i class="fas fa-user-shield me-2"></i>
                                            Military Information
                                            <span class="badge bg-primary ms-2 small">Required</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="service_number" class="form-label fw-semibold">
                                                    Service Number *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-id-card text-primary"></i>
                                                    </span>
                                                    <input type="text" class="form-control border-start-0" id="service_number" name="service_number"
                                                        value="{{ old('service_number', auth()->user()->service_number ?? '') }}" 
                                                        placeholder="e.g., GH123456" required>
                                                </div>
                                                @error('service_number')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="rank" class="form-label fw-semibold">
                                                    Rank *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-star text-primary"></i>
                                                    </span>
                                                    <select class="form-select border-start-0" id="rank" name="rank" required>
                                                        <option value="">Select Rank</option>
                                                        <option value="private" {{ old('rank', auth()->user()->rank ?? '') == 'private' ? 'selected' : '' }}>Private (PVT)</option>
                                                        <option value="corporal" {{ old('rank', auth()->user()->rank ?? '') == 'corporal' ? 'selected' : '' }}>Corporal (CPL)</option>
                                                        <option value="sergeant" {{ old('rank', auth()->user()->rank ?? '') == 'sergeant' ? 'selected' : '' }}>Sergeant (SGT)</option>
                                                        <option value="staff_sergeant" {{ old('rank', auth()->user()->rank ?? '') == 'staff_sergeant' ? 'selected' : '' }}>Staff Sergeant (SSG)</option>
                                                        <option value="lieutenant" {{ old('rank', auth()->user()->rank ?? '') == 'lieutenant' ? 'selected' : '' }}>Lieutenant (LT)</option>
                                                        <option value="captain" {{ old('rank', auth()->user()->rank ?? '') == 'captain' ? 'selected' : '' }}>Captain (CPT)</option>
                                                        <option value="major" {{ old('rank', auth()->user()->rank ?? '') == 'major' ? 'selected' : '' }}>Major (MAJ)</option>
                                                        <option value="colonel" {{ old('rank', auth()->user()->rank ?? '') == 'colonel' ? 'selected' : '' }}>Colonel (COL)</option>
                                                        <option value="other" {{ old('rank') == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                @error('rank')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="unit" class="form-label fw-semibold">
                                                    Unit *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-users text-primary"></i>
                                                    </span>
                                                    <input type="text" class="form-control border-start-0" id="unit" name="unit"
                                                        value="{{ old('unit', auth()->user()->unit ?? '') }}" 
                                                        placeholder="e.g., 1st Battalion" required>
                                                </div>
                                                @error('unit')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="branch" class="form-label fw-semibold">
                                                    Branch of Service *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-flag text-primary"></i>
                                                    </span>
                                                    <select class="form-select border-start-0" id="branch" name="branch" required>
                                                        <option value="">Select Branch</option>
                                                        <option value="army" {{ old('branch', auth()->user()->branch ?? '') == 'army' ? 'selected' : '' }}>Army</option>
                                                        <option value="navy" {{ old('branch', auth()->user()->branch ?? '') == 'navy' ? 'selected' : '' }}>Navy</option>
                                                        <option value="air_force" {{ old('branch', auth()->user()->branch ?? '') == 'air_force' ? 'selected' : '' }}>Air Force</option>
                                                        <option value="marines" {{ old('branch', auth()->user()->branch ?? '') == 'marines' ? 'selected' : '' }}>Marines</option>
                                                        <option value="other" {{ old('branch') == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                @error('branch')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="purpose" class="form-label fw-semibold">
                                                    Purpose of Visit *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-bullseye text-primary"></i>
                                                    </span>
                                                    <select id="purpose" name="purpose" class="form-select border-start-0" required>
                                                        <option value="">Select Purpose</option>
                                                        <option value="official_duty" {{ old('purpose') == 'official_duty' ? 'selected' : '' }}>Official Duty</option>
                                                        <option value="training" {{ old('purpose') == 'training' ? 'selected' : '' }}>Training/Exercise</option>
                                                        <option value="leave" {{ old('purpose') == 'leave' ? 'selected' : '' }}>Leave/Vacation</option>
                                                        <option value="medical" {{ old('purpose') == 'medical' ? 'selected' : '' }}>Medical Treatment</option>
                                                        <option value="course" {{ old('purpose') == 'course' ? 'selected' : '' }}>Course/Workshop</option>
                                                        <option value="transit" {{ old('purpose') == 'transit' ? 'selected' : '' }}>Transit</option>
                                                        <option value="other" {{ old('purpose') == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                @error('purpose')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information Section -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 text-primary d-flex align-items-center">
                                            <i class="fas fa-user-circle me-2"></i>
                                            Personal Information
                                            <span class="badge bg-primary ms-2 small">Required</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="guest_name" class="form-label fw-semibold">
                                                    Full Name *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </span>
                                                    <input type="text" class="form-control border-start-0" id="guest_name" name="guest_name"
                                                        value="{{ old('guest_name', auth()->user()->full_name ?? '') }}" 
                                                        placeholder="Enter your full name" required>
                                                </div>
                                                @error('guest_name')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="guest_phone" class="form-label fw-semibold">
                                                    Phone Number *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-phone text-primary"></i>
                                                    </span>
                                                    <input type="tel" class="form-control border-start-0" id="guest_phone" name="guest_phone"
                                                        value="{{ old('guest_phone', auth()->user()->phone ?? '') }}" 
                                                        placeholder="e.g., 0241234567" required>
                                                </div>
                                                @error('guest_phone')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label for="guest_email" class="form-label fw-semibold">
                                                    Email Address *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-envelope text-primary"></i>
                                                    </span>
                                                    <input type="email" class="form-control border-start-0" id="guest_email" name="guest_email"
                                                        value="{{ old('guest_email', auth()->user()->email ?? '') }}" 
                                                        placeholder="your.email@example.com" required>
                                                </div>
                                                @error('guest_email')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details Section -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 text-primary d-flex align-items-center">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Booking Details
                                            <span class="badge bg-primary ms-2 small">Required</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="number_of_beds" class="form-label fw-semibold">
                                                    Number of Beds *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-bed text-primary"></i>
                                                    </span>
                                                    <select id="number_of_beds" name="number_of_beds" class="form-select border-start-0" required>
                                                        <option value="">Select beds</option>
                                                    </select>
                                                </div>
                                                <div class="mt-2" id="bedsAvailabilityText">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Please select an accommodation first
                                                    </small>
                                                </div>
                                                @error('number_of_beds')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="check_in_date" class="form-label fw-semibold">
                                                    Check-in Date *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-sign-in-alt text-primary"></i>
                                                    </span>
                                                    <input type="date" id="check_in_date" name="check_in_date" class="form-control border-start-0"
                                                        value="{{ old('check_in_date') }}" min="{{ date('Y-m-d') }}" required>
                                                </div>
                                                @error('check_in_date')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="check_out_date" class="form-label fw-semibold">
                                                    Check-out Date *
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="fas fa-sign-out-alt text-primary"></i>
                                                    </span>
                                                    <input type="date" id="check_out_date" name="check_out_date" class="form-control border-start-0"
                                                        value="{{ old('check_out_date') }}" required>
                                                </div>
                                                @error('check_out_date')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="fas fa-clock text-primary me-1"></i>
                                                    Stay Duration
                                                </label>
                                                <div class="form-control bg-light border-0">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-semibold text-primary" id="nightsCount">0</span>
                                                        <span class="text-muted">nights</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Section -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-sticky-note me-2"></i>
                                            Additional Information
                                            <span class="badge bg-secondary ms-2 small">Optional</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="special_requests" class="form-label fw-semibold">
                                                    Special Requests or Notes
                                                </label>
                                                <textarea id="special_requests" name="special_requests" class="form-control" rows="4"
                                                    placeholder="Any special requirements, dietary restrictions, accessibility needs, or additional information...">{{ old('special_requests') }}</textarea>
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    This information helps us accommodate your needs better.
                                                </div>
                                                @error('special_requests')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light py-4 border-top">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="booking-summary-card">
                                    <h6 class="mb-3 text-primary">
                                        <i class="fas fa-receipt me-2"></i>
                                        Booking Summary
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <div class="border rounded p-3 text-center bg-white">
                                                <div class="text-primary mb-2">
                                                    <i class="fas fa-home"></i>
                                                </div>
                                                <div class="fw-bold text-dark" id="summaryRoom">Not selected</div>
                                                <small class="text-muted">Room</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="border rounded p-3 text-center bg-white">
                                                <div class="text-primary mb-2">
                                                    <i class="fas fa-bed"></i>
                                                </div>
                                                <div class="fw-bold text-dark" id="summaryBeds">0</div>
                                                <small class="text-muted">Beds</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="border rounded p-3 text-center bg-white">
                                                <div class="text-primary mb-2">
                                                    <i class="fas fa-moon"></i>
                                                </div>
                                                <div class="fw-bold text-dark" id="summaryNights">0</div>
                                                <small class="text-muted">Nights</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="submit" class="btn btn-success btn-lg px-4 w-100 mb-2" id="submitBookingBtn">
                                    <i class="fas fa-paper-plane me-2"></i> 
                                    <span class="submit-text">Complete Booking</span>
                                    <div class="spinner-border spinner-border-sm ms-2 d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                                <small class="text-muted d-block">
                                    <i class="fas fa-lock me-1"></i>
                                    Your information is secure
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingManager = new BookingManager();
    bookingManager.init();
});

class BookingManager {
    constructor() {
        this.selectedAccommodation = null;
        this.currentStep = 1;
        this.elements = {};
    }

    init() {
        this.cacheElements();
        this.bindEvents();
        this.initializeForm();
    }

    cacheElements() {
        this.elements = {
            step1Accommodation: document.getElementById('step1Accommodation'),
            step2BookingForm: document.getElementById('step2BookingForm'),
            progressBar: document.getElementById('progressBar'),
            accommodationCards: document.querySelectorAll('.accommodation-card'),
            selectButtons: document.querySelectorAll('.select-accommodation-btn'),
            changeRoomBtn: document.getElementById('changeRoomBtn'),
            
            // Selected room display elements
            selectedRoomName: document.getElementById('selectedRoomName'),
            selectedRoomTitle: document.getElementById('selectedRoomTitle'),
            selectedRoomImage: document.getElementById('selectedRoomImage'),
            selectedRoomFullName: document.getElementById('selectedRoomFullName'),
            selectedRoomLocation: document.getElementById('selectedRoomLocation'),
            selectedRoomBeds: document.getElementById('selectedRoomBeds'),
            selectedRoomBaths: document.getElementById('selectedRoomBaths'),
            selectedRoomGuests: document.getElementById('selectedRoomGuests'),
            selectedAvailableBeds: document.getElementById('selectedAvailableBeds'),
            selectedTotalBeds: document.getElementById('selectedTotalBeds'),
            
            // Form elements
            accommodationId: document.getElementById('accommodationId'),
            accommodationName: document.getElementById('accommodationName'),
            bedsSelect: document.getElementById('number_of_beds'),
            bedsAvailabilityText: document.getElementById('bedsAvailabilityText'),
            checkinInput: document.getElementById('check_in_date'),
            checkoutInput: document.getElementById('check_out_date'),
            nightsCount: document.getElementById('nightsCount'),
            summaryRoom: document.getElementById('summaryRoom'),
            summaryBeds: document.getElementById('summaryBeds'),
            summaryNights: document.getElementById('summaryNights'),
            submitBtn: document.getElementById('submitBookingBtn'),
            bookingForm: document.getElementById('bookingForm'),
            systemAlerts: document.getElementById('systemAlerts')
        };
    }

    bindEvents() {
        // Accommodation selection
        this.elements.selectButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const card = e.target.closest('.accommodation-card');
                this.selectAccommodation(card);
            });
        });

        // Change room button
        this.elements.changeRoomBtn.addEventListener('click', () => {
            this.showStep1();
        });

        // Date and bed selection events
        this.elements.checkinInput.addEventListener('change', () => this.handleDateChange());
        this.elements.checkoutInput.addEventListener('change', () => this.handleDateChange());
        this.elements.bedsSelect.addEventListener('change', () => this.updateBookingSummary());

        // Form submission
        this.elements.bookingForm.addEventListener('submit', (e) => this.handleFormSubmission(e));

        // Real-time validation
        this.setupRealTimeValidation();
    }

    initializeForm() {
        this.setDefaultDates();
        this.populateUserData();
    }

    selectAccommodation(card) {
        const accommodationData = JSON.parse(card.getAttribute('data-accommodation'));
        const featuredImage = card.getAttribute('data-featured-image');
        
        this.selectedAccommodation = accommodationData;

        // Update selected room display
        this.updateSelectedRoomDisplay(accommodationData, featuredImage);
        
        // Populate beds dropdown based on available beds
        this.populateBedsDropdown(accommodationData.available_beds);
        
        // Show step 2
        this.showStep2();
        
        // Update progress bar
        this.updateProgressBar(66);
    }

    updateSelectedRoomDisplay(data, image) {
        this.elements.selectedRoomName.textContent = data.name;
        this.elements.selectedRoomTitle.textContent = `Selected Room: ${data.name}`;
        this.elements.selectedRoomImage.src = image;
        this.elements.selectedRoomFullName.textContent = data.name;
        this.elements.selectedRoomLocation.textContent = data.location;
        this.elements.selectedRoomBeds.textContent = data.bedrooms;
        this.elements.selectedRoomBaths.textContent = data.bathrooms;
        this.elements.selectedRoomGuests.textContent = data.max_guests;
        this.elements.selectedAvailableBeds.textContent = data.available_beds;
        this.elements.selectedTotalBeds.textContent = data.total_beds;
        
        // Update form hidden fields
        this.elements.accommodationId.value = data.id;
        this.elements.accommodationName.value = data.name;
        
        // Update summary
        this.elements.summaryRoom.textContent = data.name;
    }

    populateBedsDropdown(availableBeds) {
        const bedsSelect = this.elements.bedsSelect;
        bedsSelect.innerHTML = '<option value="">Select number of beds</option>';

        if (availableBeds > 0) {
            const maxBeds = Math.min(availableBeds, 10);
            for (let i = 1; i <= maxBeds; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i} Bed${i > 1 ? 's' : ''}`;
                bedsSelect.appendChild(option);
            }
            bedsSelect.disabled = false;

            this.elements.bedsAvailabilityText.innerHTML = `
                <small class="text-success">
                    <i class="fas fa-check-circle me-1"></i>
                    ${availableBeds} bed${availableBeds > 1 ? 's' : ''} available
                </small>
            `;
        } else {
            bedsSelect.disabled = true;
            this.elements.bedsAvailabilityText.innerHTML = `
                <small class="text-danger">
                    <i class="fas fa-times-circle me-1"></i>
                    No beds available for booking
                </small>
            `;
        }
    }

    showStep1() {
        this.elements.step1Accommodation.classList.remove('d-none');
        this.elements.step2BookingForm.classList.add('d-none');
        this.currentStep = 1;
        this.updateProgressBar(25);
    }

    showStep2() {
        this.elements.step1Accommodation.classList.add('d-none');
        this.elements.step2BookingForm.classList.remove('d-none');
        this.currentStep = 2;
        this.updateProgressBar(66);
        this.updateBookingSummary();
    }

    updateProgressBar(percentage) {
        this.elements.progressBar.style.width = `${percentage}%`;
    }

    handleDateChange() {
        const checkin = this.elements.checkinInput.value;
        if (checkin) {
            const nextDay = new Date(checkin);
            nextDay.setDate(nextDay.getDate() + 1);
            this.elements.checkoutInput.min = nextDay.toISOString().split('T')[0];
            
            if (!this.elements.checkoutInput.value || new Date(this.elements.checkoutInput.value) <= new Date(checkin)) {
                this.elements.checkoutInput.value = nextDay.toISOString().split('T')[0];
            }
        }
        this.updateBookingSummary();
    }

    updateBookingSummary() {
        const beds = parseInt(this.elements.bedsSelect.value) || 0;
        const checkin = new Date(this.elements.checkinInput.value);
        const checkout = new Date(this.elements.checkoutInput.value);
        
        let nights = 0;
        if (checkin && checkout && checkout > checkin) {
            nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
        }

        this.elements.nightsCount.textContent = nights;
        this.elements.summaryNights.textContent = nights;
        this.elements.summaryBeds.textContent = beds;
    }

    setDefaultDates() {
        const today = new Date().toISOString().split('T')[0];
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];

        this.elements.checkinInput.value = today;
        this.elements.checkinInput.min = today;
        this.elements.checkoutInput.value = tomorrowStr;
        this.elements.checkoutInput.min = tomorrowStr;
    }

    populateUserData() {
        @if(auth()->check())
        const user = @json(auth()->user());
        if (user) {
            const fields = {
                'service_number': user.service_number,
                'rank': user.rank,
                'unit': user.unit,
                'branch': user.branch,
                'guest_name': user.full_name || `${user.first_name} ${user.last_name}`,
                'guest_phone': user.phone,
                'guest_email': user.email
            };

            Object.entries(fields).forEach(([fieldId, value]) => {
                const field = document.getElementById(fieldId);
                if (field && value && !field.value) {
                    field.value = value;
                }
            });
        }
        @endif
    }

    setupRealTimeValidation() {
        const fields = document.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => {
                this.clearFieldError(field);
            });
        });
    }

    validateField(field) {
        this.clearFieldError(field);

        if (field.hasAttribute('required') && !field.value.trim()) {
            this.showFieldError(field, 'This field is required');
            return false;
        }

        if (field.type === 'email' && field.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                return false;
            }
        }

        if (field.id === 'guest_phone' && field.value) {
            const phoneRegex = /^0\d{9}$/;
            if (!phoneRegex.test(field.value.replace(/\s/g, ''))) {
                this.showFieldError(field, 'Please enter a valid 10-digit phone number starting with 0');
                return false;
            }
        }

        return true;
    }

    showFieldError(field, message) {
        field.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    async handleFormSubmission(e) {
        e.preventDefault();

        // Validate all fields
        const fields = document.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Validate dates
        const checkin = new Date(this.elements.checkinInput.value);
        const checkout = new Date(this.elements.checkoutInput.value);
        if (checkout <= checkin) {
            this.showAlert('Check-out date must be after check-in date', 'danger');
            isValid = false;
        }

        // Validate beds selection
        if (!this.elements.bedsSelect.value || parseInt(this.elements.bedsSelect.value) < 1) {
            this.showAlert('Please select the number of beds', 'danger');
            isValid = false;
        }

        if (!isValid) {
            this.showAlert('Please fix the errors in the form before submitting.', 'danger');
            return;
        }

        // Show loading state
        this.setSubmitButtonLoading(true);

        try {
            // Submit the form
            this.elements.bookingForm.submit();
        } catch (error) {
            this.showAlert('An error occurred while processing your request. Please try again.', 'danger');
            this.setSubmitButtonLoading(false);
        }
    }

    setSubmitButtonLoading(loading) {
        const submitText = this.elements.submitBtn.querySelector('.submit-text');
        const spinner = this.elements.submitBtn.querySelector('.spinner-border');

        if (loading) {
            submitText.textContent = 'Processing...';
            spinner.classList.remove('d-none');
            this.elements.submitBtn.disabled = true;
        } else {
            submitText.textContent = 'Complete Booking';
            spinner.classList.add('d-none');
            this.elements.submitBtn.disabled = false;
        }
    }

    showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        this.elements.systemAlerts.appendChild(alertDiv);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}
</script>

<style>
.booking-progress .progress-step {
    transition: all 0.3s ease;
}

.accommodation-card {
    border: 2px solid transparent;
    transition: all 0.3s ease;
    cursor: pointer;
}

.accommodation-card:hover {
    border-color: #0d6efd;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.15);
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
    border-radius: 8px;
    font-weight: 600;
}

.btn-success {
    background: linear-gradient(135deg, #198754, #146c43);
    border: none;
    border-radius: 8px;
    font-weight: 600;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.btn-success:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    border-radius: 8px 0 0 8px;
}

.booking-summary-card {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #198754;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .accommodation-features .col-4 {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection