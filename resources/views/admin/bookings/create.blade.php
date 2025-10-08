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
                            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: 25%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div class="text-center">
                                <div class="progress-step bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 35px; height: 35px;">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="text-primary fw-bold small">Accommodation</div>
                            </div>
                            <div class="text-center">
                                <div class="progress-step bg-light border text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 35px; height: 35px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="text-muted small">Personal Info</div>
                            </div>
                            <div class="text-center">
                                <div class="progress-step bg-light border text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 35px; height: 35px;">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="text-muted small">Booking Details</div>
                            </div>
                            <div class="text-center">
                                <div class="progress-step bg-light border text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 35px; height: 35px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="text-muted small">Confirmation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Booking Form -->
            <div class="card">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Book Accommodation
                            </h4>
                            <p class="mb-0 opacity-75 small" id="pageAccommodationSubtitle">Select an accommodation to begin</p>
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                    </div>
                </div>

                <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="accommodation_id" id="pageAccommodationId">

                    <div class="card-body p-4">
                        <!-- System Alerts Container -->
                        <div id="systemAlerts"></div>

                        <!-- Accommodation Selection Alert -->
                        <div class="alert alert-info border-0 mb-4" id="selectionAlert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-3"></i>
                                <div class="flex-grow-1">
                                    <strong class="d-block mb-1">Select an Accommodation</strong>
                                    Choose from available accommodations below to start your booking process.
                                </div>
                            </div>
                        </div>

                        <!-- Selected Accommodation Details -->
                        <div class="card border-primary mb-4" id="selectedAccommodationCard" style="display: none;">
                            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-home me-2"></i>
                                    Selected Accommodation
                                </h5>
                                <button type="button" class="btn btn-sm btn-outline-light" id="changeAccommodationBtn">
                                    <i class="fas fa-sync me-1"></i> Change
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <img id="pageAccommodationImage" src="" alt="Accommodation Image" 
                                             class="img-fluid rounded shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                                    </div>
                                    <div class="col-md-6">
                                        <h5 id="pageAccommodationTitle" class="text-primary mb-2"></h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            <span id="pageAccommodationLocation"></span>
                                        </p>
                                        <div class="d-flex flex-wrap gap-3 text-muted small">
                                            <span><i class="fas fa-bed me-1"></i> <span id="pageBedrooms">0</span> Bedrooms</span>
                                            <span><i class="fas fa-bath me-1"></i> <span id="pageBathrooms">0</span> Bathrooms</span>
                                            <span><i class="fas fa-user me-1"></i> <span id="pageMaxGuests">0</span> Max Guests</span>
                                            <span><i class="fas fa-ruler-combined me-1"></i> <span id="pageArea">0</span> sq ft</span>
                                        </div>
                                        <div class="mt-2">
                                            <p class="text-muted mb-0 small" id="pageAccommodationDescription"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border rounded p-2 bg-light">
                                                    <h5 class="mb-0 text-success" id="pageAvailableBeds">0</h5>
                                                    <small class="text-muted">Available</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-2 bg-light">
                                                    <h5 class="mb-0 text-info" id="pageTotalBeds">0</h5>
                                                    <small class="text-muted">Total</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Accommodations List -->
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-list me-2"></i>
                                            Available Accommodations
                                        </h6>
                                        <span class="badge bg-primary">{{ count($accommodations) }}</span>
                                    </div>
                                    <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                                        @if($accommodations->isEmpty())
                                            <div class="text-center py-4">
                                                <i class="fas fa-bed fa-2x text-muted mb-3"></i>
                                                <p class="text-muted small mb-0">No accommodations available</p>
                                            </div>
                                        @else
                                            <div class="list-group list-group-flush" id="accommodationsList">
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
                                                    <button type="button" 
                                                            class="list-group-item list-group-item-action accommodation-option p-3"
                                                            data-id="{{ $accommodation->id }}"
                                                            data-name="{{ $accommodation->name }}"
                                                            data-location="{{ $accommodation->location }}"
                                                            data-bedrooms="{{ $accommodation->bedrooms }}"
                                                            data-bathrooms="{{ $accommodation->bathrooms }}"
                                                            data-max-guests="{{ $accommodation->max_guests }}"
                                                            data-area="{{ $accommodation->area ?? 'N/A' }}"
                                                            data-total-beds="{{ $accommodation->total_beds }}"
                                                            data-available-beds="{{ $accommodation->available_beds }}"
                                                            data-image="{{ $featuredUrl }}"
                                                            data-description="{{ $accommodation->description }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="position-relative me-3">
                                                                <img src="{{ $featuredUrl }}" 
                                                                     alt="{{ $accommodation->name }}"
                                                                     class="rounded" 
                                                                     style="width: 70px; height: 50px; object-fit: cover;">
                                                                <span class="position-absolute top-0 start-100 translate-middle badge {{ $availabilityClass }} border border-2">
                                                                    {{ $accommodation->available_beds }}
                                                                </span>
                                                            </div>
                                                            <div class="flex-grow-1 text-start">
                                                                <h6 class="mb-1 fw-bold">{{ Str::limit($accommodation->name, 25) }}</h6>
                                                                <div class="d-flex align-items-center mb-1">
                                                                    <i class="fas fa-map-marker-alt text-muted me-1 fa-xs"></i>
                                                                    <small class="text-muted">{{ Str::limit($accommodation->location, 22) }}</small>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas {{ $availabilityIcon }} {{ $availabilityText }} me-1 fa-xs"></i>
                                                                    <small class="{{ $availabilityText }} fw-semibold">
                                                                        {{ $accommodation->available_beds }}/{{ $accommodation->total_beds }} beds
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <i class="fas fa-chevron-right text-muted ms-2"></i>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Form Sections -->
                            <div class="col-lg-7">
                                <!-- Military Information Section -->
                                <div class="card border-0 shadow-sm mb-4">
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

                                <!-- Personal Information Section -->
                                <div class="card border-0 shadow-sm mb-4">
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

                                <!-- Booking Details Section -->
                                <div class="card border-0 shadow-sm mb-4">
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
                                                        <span class="fw-semibold text-primary" id="pageNights">0</span>
                                                        <span class="text-muted">nights</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information Section -->
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
                                                <div class="fw-bold text-dark" id="summaryAccommodation">Not selected</div>
                                                <small class="text-muted">Accommodation</small>
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
                                <button type="submit" class="btn btn-primary btn-lg px-4 w-100 mb-2" id="submitBookingBtn" disabled>
                                    <i class="fas fa-paper-plane me-2"></i> 
                                    <span class="submit-text">Submit Booking</span>
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
        this.currentAvailableBeds = 0;
        this.currentTotalBeds = 0;
        this.selectedAccommodation = null;
        this.elements = {};
    }

    init() {
        this.cacheElements();
        this.bindEvents();
        this.initializeForm();
        // Removed autoSelectFirstAccommodation - no default selection
    }

    cacheElements() {
        this.elements = {
            accommodationOptions: document.querySelectorAll('.accommodation-option'),
            selectedAccommodationCard: document.getElementById('selectedAccommodationCard'),
            selectionAlert: document.getElementById('selectionAlert'),
            accommodationId: document.getElementById('pageAccommodationId'),
            accommodationName: document.getElementById('pageAccommodationName'),
            accommodationSubtitle: document.getElementById('pageAccommodationSubtitle'),
            accommodationTitle: document.getElementById('pageAccommodationTitle'),
            accommodationLocation: document.getElementById('pageAccommodationLocation'),
            accommodationImage: document.getElementById('pageAccommodationImage'),
            bedrooms: document.getElementById('pageBedrooms'),
            bathrooms: document.getElementById('pageBathrooms'),
            maxGuests: document.getElementById('pageMaxGuests'),
            area: document.getElementById('pageArea'),
            availableBeds: document.getElementById('pageAvailableBeds'),
            totalBeds: document.getElementById('pageTotalBeds'),
            accommodationDescription: document.getElementById('pageAccommodationDescription'),
            bedsSelect: document.getElementById('number_of_beds'),
            bedsAvailabilityText: document.getElementById('bedsAvailabilityText'),
            checkinInput: document.getElementById('check_in_date'),
            checkoutInput: document.getElementById('check_out_date'),
            nightsDisplay: document.getElementById('pageNights'),
            summaryAccommodation: document.getElementById('summaryAccommodation'),
            summaryBeds: document.getElementById('summaryBeds'),
            summaryNights: document.getElementById('summaryNights'),
            submitBtn: document.getElementById('submitBookingBtn'),
            bookingForm: document.getElementById('bookingForm'),
            changeAccommodationBtn: document.getElementById('changeAccommodationBtn'),
            systemAlerts: document.getElementById('systemAlerts')
        };
    }

    bindEvents() {
        // Accommodation selection
        this.elements.accommodationOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                console.log('Accommodation clicked:', option.dataset.name);
                this.selectAccommodation(option);
            });
        });

        // Date and bed selection events
        if (this.elements.checkinInput) {
            this.elements.checkinInput.addEventListener('change', () => this.handleDateChange());
        }
        if (this.elements.checkoutInput) {
            this.elements.checkoutInput.addEventListener('change', () => this.handleDateChange());
        }
        if (this.elements.bedsSelect) {
            this.elements.bedsSelect.addEventListener('change', () => this.updateBookingSummary());
        }

        // Change accommodation button
        if (this.elements.changeAccommodationBtn) {
            this.elements.changeAccommodationBtn.addEventListener('click', () => this.showAccommodationSelection());
        }

        // Form submission
        if (this.elements.bookingForm) {
            this.elements.bookingForm.addEventListener('submit', (e) => this.handleFormSubmission(e));
        }

        // Real-time validation
        this.setupRealTimeValidation();
    }

    initializeForm() {
        this.setDefaultDates();
        this.populateUserData();
    }

    selectAccommodation(option) {
        console.log('Selecting accommodation:', option.dataset.name);
        console.log('Available beds:', option.dataset.availableBeds);
        
        // Remove active class from all options
        this.elements.accommodationOptions.forEach(opt => {
            opt.classList.remove('active', 'border-primary');
        });

        // Add active class to selected option
        option.classList.add('active', 'border-primary');

        // Get accommodation data
        const accommodationData = {
            id: option.dataset.id,
            name: option.dataset.name,
            location: option.dataset.location,
            image: option.dataset.image,
            bedrooms: option.dataset.bedrooms,
            bathrooms: option.dataset.bathrooms,
            maxGuests: option.dataset.maxGuests,
            area: option.dataset.area,
            totalBeds: parseInt(option.dataset.totalBeds),
            availableBeds: parseInt(option.dataset.availableBeds),
            description: option.dataset.description
        };

        this.selectedAccommodation = accommodationData;
        this.currentAvailableBeds = accommodationData.availableBeds;
        this.currentTotalBeds = accommodationData.totalBeds;

        this.updateAccommodationDisplay(accommodationData);
        this.populateBedsDropdown();
        this.showSelectedAccommodation();
        this.updateBookingSummary();
    }

    updateAccommodationDisplay(data) {
        this.elements.accommodationId.value = data.id;
        this.elements.accommodationName.textContent = data.name;
        this.elements.accommodationSubtitle.textContent = data.name;
        this.elements.accommodationTitle.textContent = data.name;
        this.elements.accommodationLocation.textContent = data.location;
        this.elements.accommodationImage.src = data.image;
        this.elements.accommodationImage.onerror = function() {
            this.src = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80';
        };
        this.elements.bedrooms.textContent = data.bedrooms;
        this.elements.bathrooms.textContent = data.bathrooms;
        this.elements.maxGuests.textContent = data.maxGuests;
        this.elements.area.textContent = data.area;
        this.elements.availableBeds.textContent = data.availableBeds;
        this.elements.totalBeds.textContent = data.totalBeds;
        this.elements.accommodationDescription.textContent = data.description;
        this.elements.summaryAccommodation.textContent = data.name;
    }

    populateBedsDropdown() {
        console.log('Populating beds dropdown. Available beds:', this.currentAvailableBeds);
        
        const bedsSelect = this.elements.bedsSelect;
        bedsSelect.innerHTML = '<option value="">Select number of beds</option>';

        if (this.currentAvailableBeds > 0) {
            const maxBeds = Math.min(this.currentAvailableBeds, 10);
            for (let i = 1; i <= maxBeds; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i} Bed${i > 1 ? 's' : ''}`;
                bedsSelect.appendChild(option);
            }
            bedsSelect.disabled = false;

            // Update availability text
            this.elements.bedsAvailabilityText.innerHTML = `
                <small class="text-success">
                    <i class="fas fa-check-circle me-1"></i>
                    ${this.currentAvailableBeds} bed${this.currentAvailableBeds > 1 ? 's' : ''} available
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

        // Force UI update
        bedsSelect.dispatchEvent(new Event('change'));
    }

    showSelectedAccommodation() {
        this.elements.selectedAccommodationCard.style.display = 'block';
        this.elements.selectionAlert.style.display = 'none';
        this.updateSubmitButtonState();
    }

    showAccommodationSelection() {
        this.elements.selectedAccommodationCard.style.display = 'none';
        this.elements.selectionAlert.style.display = 'block';
        this.disableSubmitButton();
        
        // Reset beds dropdown
        this.elements.bedsSelect.innerHTML = '<option value="">Select number of beds</option>';
        this.elements.bedsSelect.disabled = true;
        this.elements.bedsAvailabilityText.innerHTML = `
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Please select an accommodation first
            </small>
        `;
        
        // Remove active class from all options
        this.elements.accommodationOptions.forEach(opt => {
            opt.classList.remove('active', 'border-primary');
        });
        
        this.selectedAccommodation = null;
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

        this.elements.nightsDisplay.textContent = nights;
        this.elements.summaryNights.textContent = nights;
        this.elements.summaryBeds.textContent = beds;

        this.updateSubmitButtonState();
    }

    setDefaultDates() {
        const today = new Date().toISOString().split('T')[0];
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];

        if (this.elements.checkinInput) {
            this.elements.checkinInput.value = today;
            this.elements.checkinInput.min = today;
        }
        if (this.elements.checkoutInput) {
            this.elements.checkoutInput.value = tomorrowStr;
            this.elements.checkoutInput.min = tomorrowStr;
        }
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
                this.updateSubmitButtonState();
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

    updateSubmitButtonState() {
        const isAccommodationSelected = this.selectedAccommodation !== null;
        const areDatesValid = this.elements.checkinInput.value && this.elements.checkoutInput.value;
        const areBedsSelected = this.elements.bedsSelect.value && this.elements.bedsSelect.value > 0;
        
        const requiredFields = [
            'service_number', 'rank', 'unit', 'branch', 'purpose',
            'guest_name', 'guest_phone', 'guest_email'
        ];

        const allRequiredFieldsFilled = requiredFields.every(fieldId => {
            const field = document.getElementById(fieldId);
            return field && field.value.trim();
        });

        if (isAccommodationSelected && areDatesValid && areBedsSelected && allRequiredFieldsFilled) {
            this.enableSubmitButton();
        } else {
            this.disableSubmitButton();
        }
    }

    enableSubmitButton() {
        this.elements.submitBtn.disabled = false;
        this.elements.submitBtn.classList.remove('btn-secondary');
        this.elements.submitBtn.classList.add('btn-primary');
    }

    disableSubmitButton() {
        this.elements.submitBtn.disabled = true;
        this.elements.submitBtn.classList.remove('btn-primary');
        this.elements.submitBtn.classList.add('btn-secondary');
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

        if (!isValid) {
            this.showAlert('Please fix the errors in the form before submitting.', 'danger');
            return;
        }

        // Show loading state
        this.setSubmitButtonLoading(true);

        try {
            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            // If we get here, form is valid - submit it
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
            submitText.textContent = 'Submit Booking';
            spinner.classList.add('d-none');
            this.updateSubmitButtonState();
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

.accommodation-option {
    border: 2px solid transparent;
    transition: all 0.3s ease;
    cursor: pointer;
}

.accommodation-option:hover {
    border-color: #e9ecef;
    background-color: #f8f9fa;
    transform: translateY(-1px);
}

.accommodation-option.active {
    border-color: #0d6efd !important;
    background-color: #f0f8ff;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.list-group-item {
    border: none;
    padding: 1rem;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef !important;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
    border-radius: 8px;
    font-weight: 600;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
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
    border-left: 4px solid #0d6efd;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}

/* Custom scrollbar */
.card-body::-webkit-scrollbar {
    width: 6px;
}

.card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.card-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endsection