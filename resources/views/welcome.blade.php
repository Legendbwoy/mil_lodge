@extends('layouts.client')

@section('title', 'Akafia | Opong Peprah Lodge')
@section('page-title', 'Available Accommodations')

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <!-- [ Main Content ] start -->
    <div class="container mt-4">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="validation-errors">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Hero Section -->
        <div class="hero-section mb-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold text-primary mb-3">Find Your Perfect Stay</h1>
                    <p class="lead mb-4">Discover premium accommodations with exceptional amenities and unbeatable comfort
                        for your next getaway.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark p-2"><i class="fas fa-wifi text-primary me-1"></i> Free
                            WiFi</span>
                        <span class="badge bg-light text-dark p-2"><i class="fas fa-swimming-pool text-primary me-1"></i>
                            Swimming Pool</span>
                        <span class="badge bg-light text-dark p-2"><i class="fas fa-utensils text-primary me-1"></i>
                            Restaurant</span>
                        <span class="badge bg-light text-dark p-2"><i class="fas fa-car text-primary me-1"></i> Free
                            Parking</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image rounded-3 overflow-hidden shadow-lg">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80"
                            alt="Luxury Accommodation" class="img-fluid"
                            style="height: 250px; width: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Compact Search and Filter Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <h5 class="section-title mb-0">Search Available Accommodations</h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted">{{ $accommodations->total() }} available properties</small>
                    </div>
                </div>

                <!-- Compact Search and Filter Section -->
                <form action="{{ route('home') }}" method="GET">
                    <div class="row g-2 align-items-end">
                        <!-- Location Search -->
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold mb-1">Location</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 py-2">
                                    <i class="fas fa-map-marker-alt text-primary small"></i>
                                </span>
                                <input type="text" name="location" class="form-control border-start-0 ps-1 py-2" 
                                    placeholder="Search location..." value="{{ request('location') }}">
                            </div>
                        </div>

                        <!-- Accommodation Type -->
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold mb-1">Type</label>
                            <select name="type" class="form-control form-control-sm py-2">
                                <option value="">All Types</option>
                                <option value="barracks" {{ request('type') == 'barracks' ? 'selected' : '' }}>Barracks</option>
                                <option value="family_quarters" {{ request('type') == 'family_quarters' ? 'selected' : '' }}>Family Quarters</option>
                                <option value="voq" {{ request('type') == 'voq' ? 'selected' : '' }}>VOQ</option>
                                <option value="tlq" {{ request('type') == 'tlq' ? 'selected' : '' }}>TLQ</option>
                                <option value="recreation_lodge" {{ request('type') == 'recreation_lodge' ? 'selected' : '' }}>Recreation</option>
                            </select>
                        </div>

                        <!-- Available Beds Filter -->
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold mb-1">Min Beds Available</label>
                            <select name="min_beds" class="form-control form-control-sm py-2">
                                <option value="">Any</option>
                                <option value="1" {{ request('min_beds') == '1' ? 'selected' : '' }}>1+ Bed</option>
                                <option value="2" {{ request('min_beds') == '2' ? 'selected' : '' }}>2+ Beds</option>
                                <option value="3" {{ request('min_beds') == '3' ? 'selected' : '' }}>3+ Beds</option>
                                <option value="5" {{ request('min_beds') == '5' ? 'selected' : '' }}>5+ Beds</option>
                            </select>
                        </div>

                        <!-- Sort Options -->
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold mb-1">Sort By</label>
                            <select name="sort" class="form-control form-control-sm py-2">
                                <option value="">Default</option>
                                <option value="available_beds_desc" {{ request('sort') == 'available_beds_desc' ? 'selected' : '' }}>Most Beds Available</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Highest Rated</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-fill py-2">
                                    <i class="fas fa-search me-1"></i> Search
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm py-2" title="Reset Filters">
                                    <i class="fas fa-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if (request()->anyFilled(['location', 'type', 'min_beds', 'sort']))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center flex-wrap gap-1">
                                    <span class="text-muted small">Active filters:</span>
                                    @if (request('location'))
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small py-1 px-2">
                                            Location: {{ request('location') }}
                                            <a href="{{ request()->fullUrlWithQuery(['location' => null]) }}" class="text-primary ms-1">
                                                <i class="fas fa-times small"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if (request('type'))
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small py-1 px-2">
                                            Type: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="text-primary ms-1">
                                                <i class="fas fa-times small"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if (request('min_beds'))
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small py-1 px-2">
                                            Min Beds: {{ request('min_beds') }}+
                                            <a href="{{ request()->fullUrlWithQuery(['min_beds' => null]) }}" class="text-primary ms-1">
                                                <i class="fas fa-times small"></i>
                                            </a>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Accommodation Cards -->
        <div class="row" id="accommodationsContainer">
            @forelse($accommodations as $accommodation)
                @php
                    // Normalize images array and convert stored paths to accessible URLs
                    $images = [];
                    if (is_array($accommodation->images)) {
                        $images = $accommodation->images;
                    } else {
                        // if stored as JSON string, try decode
                        $decoded = json_decode($accommodation->images, true);
                        if (is_array($decoded)) {
                            $images = $decoded;
                        }
                    }

                    // convert each image path to full URL (if not already)
                    $imageUrls = array_map(function ($p) {
                        if (!$p) {
                            return null;
                        }
                        if (Str::startsWith($p, ['http://', 'https://', '/storage/'])) {
                            return $p;
                        }
                        // assume stored path like "accommodations/xxx.jpg"
                        return Storage::url($p);
                    }, array_filter($images));

                    // ensure featured image URL string (fallback to placeholder)
                    $featuredUrl = count($imageUrls)
                        ? $imageUrls[0]
                        : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80';

                @endphp

                <div class="col-lg-4 col-md-6 mb-4 accommodation-item"
                    data-type="{{ $accommodation->type }}" 
                    data-bedrooms="{{ $accommodation->bedrooms }}"
                    data-available-beds="{{ $accommodation->available_beds }}"
                    data-total-beds="{{ $accommodation->total_beds }}"
                    data-rating="{{ $accommodation->average_rating }}">
                    <div class="card accommodation-card h-100 shadow-sm border-0 hover-lift">
                        <div class="card-img-container position-relative overflow-hidden">
                            @if ($accommodation->is_featured)
                                <span class="featured-badge position-absolute top-0 start-0 m-2 badge bg-warning text-dark small">
                                    <i class="fas fa-star me-1"></i> Featured
                                </span>
                            @endif

                            @if (!$accommodation->is_available || $accommodation->available_beds == 0)
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
                                    <span class="badge bg-danger py-1 px-2 small">Fully Booked</span>
                                </div>
                            @endif

                            <img src="{{ $featuredUrl }}" class="card-img-top main-image"
                                alt="{{ $accommodation->name }}"
                                style="height:200px; object-fit:cover; width:100%; transition: transform 0.3s ease;">

                            <div class="img-overlay p-2 position-absolute bottom-0 start-0 w-100"
                                style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);">
                                <div class="text-white">
                                    <h6 class="mb-1 fw-bold">{{ $accommodation->name }}</h6>
                                    <small class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $accommodation->location }}
                                    </small>
                                </div>
                            </div>

                            <!-- Quick Actions Overlay -->
                            <div class="quick-actions position-absolute top-50 start-50 translate-middle opacity-0"
                                style="transition: opacity 0.3s ease;">
                                <div class="btn-group">
                                    <button class="btn btn-light btn-sm view-details-btn" data-bs-toggle="modal"
                                        data-bs-target="#detailsModal" data-accommodation-id="{{ $accommodation->id }}"
                                        data-images='@json($imageUrls)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if ($accommodation->is_available && $accommodation->available_beds > 0)
                                        <button class="btn btn-primary btn-sm book-now-btn" data-bs-toggle="modal"
                                            data-bs-target="#bookingModal" data-accommodation-id="{{ $accommodation->id }}"
                                            data-accommodation-name="{{ $accommodation->name }}"
                                            data-available-beds="{{ $accommodation->available_beds }}"
                                            data-total-beds="{{ $accommodation->total_beds }}">
                                            <i class="fas fa-calendar-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="rating small">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($accommodation->average_rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i == ceil($accommodation->average_rating) && $accommodation->average_rating != floor($accommodation->average_rating))
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="text-muted ms-1">({{ number_format($accommodation->average_rating, 1) }})</span>
                                </div>

                                <div class="text-muted small">
                                    <i class="fas fa-bed me-1 text-primary"></i> {{ $accommodation->bedrooms }}
                                    <i class="fas fa-bath ms-2 me-1 text-primary"></i> {{ $accommodation->bathrooms }}
                                </div>
                            </div>

                            <p class="card-text text-muted small mb-3 flex-grow-1">
                                {{ Str::limit($accommodation->description, 100) }}
                            </p>

                            <!-- Bed Availability Section -->
                            <div class="bed-info mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted small">
                                        <i class="fas fa-bed text-primary me-1"></i>
                                        Beds Available:
                                    </span>
                                    <span class="fw-bold text-{{ $accommodation->available_beds > 0 ? 'success' : 'danger' }} small">
                                        {{ $accommodation->available_beds }}/{{ $accommodation->total_beds }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-{{ $accommodation->available_beds > 0 ? 'success' : 'danger' }}"
                                        style="width: {{ ($accommodation->available_beds / $accommodation->total_beds) * 100 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <button class="btn btn-outline-primary btn-sm view-details-btn" data-bs-toggle="modal"
                                    data-bs-target="#detailsModal" data-accommodation-id="{{ $accommodation->id }}"
                                    data-images='@json($imageUrls)'>
                                    <i class="fas fa-info-circle me-1"></i> Details
                                </button>

                                @if ($accommodation->is_available && $accommodation->available_beds > 0)
                                    <button class="btn btn-primary btn-sm book-now-btn" data-bs-toggle="modal"
                                        data-bs-target="#bookingModal" data-accommodation-id="{{ $accommodation->id }}"
                                        data-accommodation-name="{{ $accommodation->name }}"
                                        data-available-beds="{{ $accommodation->available_beds }}"
                                        data-total-beds="{{ $accommodation->total_beds }}">
                                        <i class="fas fa-calendar-check me-1"></i> Book Now
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-times me-1"></i> No Beds
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No accommodations found</h4>
                            <p class="text-muted mb-4">Try adjusting your search criteria or <a
                                    href="{{ route('home') }}" class="text-primary">view all accommodations</a>.</p>
                            <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('home') }}'">
                                <i class="fas fa-refresh me-2"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($accommodations->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                <div class="card">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="text-muted small mb-2 mb-md-0">
                                Showing {{ $accommodations->firstItem() ?? 0 }} to {{ $accommodations->lastItem() ?? 0 }}
                                of {{ $accommodations->total() }} results
                            </div>

                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                <li class="page-item {{ $accommodations->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $accommodations->previousPageUrl() }}"
                                        aria-label="Previous">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>

                                {{-- Page Numbers --}}
                                @foreach (range(1, $accommodations->lastPage()) as $page)
                                    @if ($page >= $accommodations->currentPage() - 2 && $page <= $accommodations->currentPage() + 2)
                                        <li
                                            class="page-item {{ $page == $accommodations->currentPage() ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="{{ $accommodations->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                <li class="page-item {{ !$accommodations->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $accommodations->nextPageUrl() }}" aria-label="Next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        @endif
    </div>
    <!-- [ Main Content ] end -->

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="detailsModalLabel" class="modal-title">Accommodation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="detailsModalBody">
                    <!-- details will be injected by JS -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading accommodation details...</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="detailsBookBtn" style="display:none;">
                        <i class="fas fa-calendar-check me-1"></i> Book Now
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 id="bookingModalLabel" class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Book Beds - <span id="modalAccommodationName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}">
                @csrf
                <input type="hidden" name="accommodation_id" id="modalAccommodationId">

                <div class="modal-body">
                    <!-- Availability Alert -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div class="flex-grow-1">
                                <strong class="d-block mb-1">Bed-Based Booking</strong>
                                This room has <span id="modalTotalBeds" class="fw-bold"></span> beds total, 
                                with <span id="modalAvailableBeds" class="fw-bold text-success"></span> currently available.
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Military Information Section -->
                        <div class="col-12">
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-header bg-transparent border-0 py-3">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-user-shield me-2"></i>
                                        Military Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Service Number -->
                                        <div class="col-md-6">
                                            <label for="service_number" class="form-label fw-semibold">
                                                <i class="fas fa-id-card text-primary me-2"></i>
                                                Service Number *
                                            </label>
                                            <input type="text" class="form-control" id="service_number" name="service_number"
                                                value="{{ old('service_number', auth()->user()->service_number ?? '') }}" 
                                                placeholder="Enter your service number" required>
                                            @error('service_number')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Rank -->
                                        <div class="col-md-6">
                                            <label for="rank" class="form-label fw-semibold">
                                                <i class="fas fa-star text-primary me-2"></i>
                                                Rank *
                                            </label>
                                            <select class="form-control" id="rank" name="rank" required>
                                                <option value="">Select Rank</option>
                                                <option value="private" {{ old('rank', auth()->user()->rank ?? '') == 'private' ? 'selected' : '' }}>Private (PVT)</option>
                                                <option value="corporal" {{ old('rank', auth()->user()->rank ?? '') == 'corporal' ? 'selected' : '' }}>Corporal (CPL)</option>
                                                <option value="sergeant" {{ old('rank', auth()->user()->rank ?? '') == 'sergeant' ? 'selected' : '' }}>Sergeant (SGT)</option>
                                                <option value="lieutenant" {{ old('rank', auth()->user()->rank ?? '') == 'lieutenant' ? 'selected' : '' }}>Lieutenant (LT)</option>
                                                <option value="captain" {{ old('rank', auth()->user()->rank ?? '') == 'captain' ? 'selected' : '' }}>Captain (CPT)</option>
                                                <option value="major" {{ old('rank', auth()->user()->rank ?? '') == 'major' ? 'selected' : '' }}>Major (MAJ)</option>
                                                <option value="colonel" {{ old('rank', auth()->user()->rank ?? '') == 'colonel' ? 'selected' : '' }}>Colonel (COL)</option>
                                                <option value="other" {{ old('rank') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('rank')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Unit -->
                                        <div class="col-md-6">
                                            <label for="unit" class="form-label fw-semibold">
                                                <i class="fas fa-users text-primary me-2"></i>
                                                Unit *
                                            </label>
                                            <input type="text" class="form-control" id="unit" name="unit"
                                                value="{{ old('unit', auth()->user()->unit ?? '') }}" 
                                                placeholder="e.g., 1st Battalion" required>
                                            @error('unit')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Branch -->
                                        <div class="col-md-6">
                                            <label for="branch" class="form-label fw-semibold">
                                                <i class="fas fa-flag text-primary me-2"></i>
                                                Branch *
                                            </label>
                                            <select class="form-control" id="branch" name="branch" required>
                                                <option value="">Select Branch</option>
                                                <option value="army" {{ old('branch', auth()->user()->branch ?? '') == 'army' ? 'selected' : '' }}>Army</option>
                                                <option value="navy" {{ old('branch', auth()->user()->branch ?? '') == 'navy' ? 'selected' : '' }}>Navy</option>
                                                <option value="air_force" {{ old('branch', auth()->user()->branch ?? '') == 'air_force' ? 'selected' : '' }}>Air Force</option>
                                                <option value="marines" {{ old('branch', auth()->user()->branch ?? '') == 'marines' ? 'selected' : '' }}>Marines</option>
                                                <option value="other" {{ old('branch') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('branch')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Purpose of Booking -->
                                        <div class="col-12">
                                            <label for="purpose" class="form-label fw-semibold">
                                                <i class="fas fa-bullseye text-primary me-2"></i>
                                                Purpose of Booking *
                                            </label>
                                            <select id="purpose" name="purpose" class="form-select" required>
                                                <option value="">Select Purpose</option>
                                                <option value="official_visit" {{ old('purpose') == 'official_visit' ? 'selected' : '' }}>Official Visit</option>
                                                <option value="training" {{ old('purpose') == 'training' ? 'selected' : '' }}>Training</option>
                                                <option value="vacation" {{ old('purpose') == 'vacation' ? 'selected' : '' }}>Vacation/Leave</option>
                                                <option value="medical" {{ old('purpose') == 'medical' ? 'selected' : '' }}>Medical</option>
                                                <option value="course" {{ old('purpose') == 'course' ? 'selected' : '' }}>Course/Workshop</option>
                                                <option value="transit" {{ old('purpose') == 'transit' ? 'selected' : '' }}>Transit</option>
                                                <option value="other" {{ old('purpose') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('purpose')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="col-12">
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-header bg-transparent border-0 py-3">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-user-circle me-2"></i>
                                        Personal Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Full Name -->
                                        <div class="col-md-6">
                                            <label for="guest_name" class="form-label fw-semibold">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                Full Name *
                                            </label>
                                            <input type="text" class="form-control" id="guest_name" name="guest_name"
                                                value="{{ old('guest_name', auth()->user()->full_name ?? '') }}" 
                                                placeholder="Enter your full name" required>
                                            @error('guest_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-6">
                                            <label for="guest_phone" class="form-label fw-semibold">
                                                <i class="fas fa-phone text-primary me-2"></i>
                                                Phone Number *
                                            </label>
                                            <input type="tel" class="form-control" id="guest_phone" name="guest_phone"
                                                value="{{ old('guest_phone', auth()->user()->phone ?? '') }}" 
                                                placeholder="e.g., 0241234567" required>
                                            @error('guest_phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="col-12">
                                            <label for="guest_email" class="form-label fw-semibold">
                                                <i class="fas fa-envelope text-primary me-2"></i>
                                                Email Address *
                                            </label>
                                            <input type="email" class="form-control" id="guest_email" name="guest_email"
                                                value="{{ old('guest_email', auth()->user()->email ?? '') }}" 
                                                placeholder="your.email@example.com" required>
                                            @error('guest_email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details Section -->
                        <div class="col-12">
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-header bg-transparent border-0 py-3">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Booking Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Number of Beds -->
                                        <div class="col-md-6">
                                            <label for="number_of_beds" class="form-label fw-semibold">
                                                <i class="fas fa-bed text-primary me-2"></i>
                                                Number of Beds *
                                            </label>
                                            <select id="number_of_beds" name="number_of_beds" class="form-select" required>
                                                <option value="">Select beds</option>
                                                <!-- Options will be populated by JavaScript -->
                                            </select>
                                            <small class="form-text text-muted mt-1" id="bedsAvailabilityText">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Checking availability...
                                            </small>
                                            @error('number_of_beds')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Check-in Date -->
                                        <div class="col-md-6">
                                            <label for="check_in_date" class="form-label fw-semibold">
                                                <i class="fas fa-sign-in-alt text-primary me-2"></i>
                                                Check-in Date *
                                            </label>
                                            <input type="date" id="check_in_date" name="check_in_date" class="form-control"
                                                value="{{ old('check_in_date') }}" min="{{ date('Y-m-d') }}" required>
                                            @error('check_in_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Check-out Date -->
                                        <div class="col-md-6">
                                            <label for="check_out_date" class="form-label fw-semibold">
                                                <i class="fas fa-sign-out-alt text-primary me-2"></i>
                                                Check-out Date *
                                            </label>
                                            <input type="date" id="check_out_date" name="check_out_date" class="form-control"
                                                value="{{ old('check_out_date') }}" required>
                                            @error('check_out_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Duration Display -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-muted">
                                                <i class="fas fa-clock text-primary me-2"></i>
                                                Duration
                                            </label>
                                            <div class="form-control bg-white border">
                                                <span id="modalNights" class="fw-bold text-primary">0</span> nights
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-transparent border-0 py-3">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-sticky-note me-2"></i>
                                        Additional Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Special Requests -->
                                        <div class="col-12">
                                            <label for="special_requests" class="form-label fw-semibold">
                                                <i class="fas fa-comment-dots text-primary me-2"></i>
                                                Additional Notes or Special Requests
                                            </label>
                                            <textarea id="special_requests" name="special_requests" class="form-control" rows="4"
                                                placeholder="Any additional information, special requirements, or specific needs...">{{ old('special_requests') }}</textarea>
                                            <small class="form-text text-muted">
                                                Please provide any additional information that might help us accommodate your needs better.
                                            </small>
                                            @error('special_requests')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Summary -->
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white py-3">
                                    <h6 class="mb-0">
                                        <i class="fas fa-receipt me-2"></i>
                                        Booking Summary
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="mb-2">
                                                <strong class="text-muted">Accommodation:</strong>
                                                <span id="modalAccommodationInfo" class="fw-semibold ms-2"></span>
                                            </div>
                                            <div class="mb-2">
                                                <strong class="text-muted">Beds:</strong>
                                                <span id="modalBedsCount" class="fw-semibold ms-2">0</span> beds × 
                                                <span id="modalNightsSummary" class="fw-semibold">0</span> nights
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <i class="fas fa-file-invoice-dollar fa-3x text-primary opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBookingBtn">
                        <i class="fas fa-paper-plane me-2"></i> Submit Booking Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.35rem 0.75rem rgba(0, 0, 0, 0.15) !important;
    }

    .accommodation-card:hover .quick-actions {
        opacity: 1 !important;
    }

    .accommodation-card:hover .main-image {
        transform: scale(1.05);
    }

    .section-title {
        position: relative;
        padding-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background: linear-gradient(90deg, #0d6efd, #6f42c1);
        border-radius: 2px;
    }

    .featured-badge {
        font-size: 0.7rem;
        font-weight: 600;
    }

    .rating i {
        font-size: 0.8rem;
    }

    .quick-actions .btn-group .btn {
        border-radius: 0.25rem;
        margin: 0 1px;
        padding: 0.25rem 0.5rem;
    }

    /* Compact form styles */
    .form-control-sm {
        font-size: 0.875rem;
    }

    /* Ensure modals are scrollable and properly styled */
    .modal-dialog-scrollable .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-body {
        max-height: calc(90vh - 200px);
        overflow-y: auto;
    }

    /* Make sure submit button is always clickable by default */
    #submitBookingBtn {
        cursor: pointer;
    }

    #submitBookingBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Ensure buttons are clickable */
    .book-now-btn, .view-details-btn {
        cursor: pointer;
        z-index: 10;
        position: relative;
    }

    /* Style for beds dropdown */
    #number_of_beds:disabled {
        background-color: #e9ecef;
        opacity: 0.7;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-section {
            padding: 1.5rem 1rem;
            text-align: center;
        }

        .hero-section .display-5 {
            font-size: 1.75rem;
        }

        .search-filter .col-md-3 {
            margin-bottom: 0.5rem;
        }

        .quick-actions {
            display: none !important;
        }
        
        .card-img-top {
            height: 180px !important;
        }
    }

    @media (max-width: 576px) {
        .hero-section .display-5 {
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 1rem !important;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Helpers ---
    const qs = s => document.querySelector(s);
    const qsa = s => Array.from(document.querySelectorAll(s));

    // Add real-time search functionality
    const searchForm = document.querySelector('form[action="{{ route("home") }}"]');
    if (searchForm) {
        const inputs = searchForm.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Add a small delay for better UX
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 500);
            });
        });
    }

    // Quick filter buttons for common searches
    const quickFilters = [
        { text: 'Available Now', query: 'min_beds=1' },
        { text: 'Multiple Beds', query: 'min_beds=3' },
        { text: 'Barracks', query: 'type=barracks' },
        { text: 'Family Quarters', query: 'type=family_quarters' }
    ];

    // Initialize Bootstrap modals
    const detailsModalElement = document.getElementById('detailsModal');
    const bookingModalElement = document.getElementById('bookingModal');
    
    let detailsModal = null;
    let bookingModal = null;
    
    if (detailsModalElement) {
        detailsModal = new bootstrap.Modal(detailsModalElement);
    }
    if (bookingModalElement) {
        bookingModal = new bootstrap.Modal(bookingModalElement);
    }

    // --- Hover effects for cards ---
    qsa('.accommodation-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow');
        });

        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow');
        });
    });

    // --- Booking modal logic ---
    let currentAccommodationId = null;
    let currentAvailableBeds = 0;
    let currentTotalBeds = 0;

    // Fix for book-now buttons - use event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.book-now-btn')) {
            const btn = e.target.closest('.book-now-btn');
            e.preventDefault();
            
            console.log('Book now button clicked', btn.dataset);
            
            currentAccommodationId = btn.dataset.accommodationId;
            const accommodationName = btn.dataset.accommodationName || '';
            currentAvailableBeds = parseInt(btn.dataset.availableBeds) || 0;
            currentTotalBeds = parseInt(btn.dataset.totalBeds) || 0;

            console.log('Available beds:', currentAvailableBeds, 'Total beds:', currentTotalBeds);

            // Set accommodation info in modal
            qs('#modalAccommodationId').value = currentAccommodationId;
            qs('#modalAccommodationName').textContent = accommodationName;
            qs('#modalAccommodationInfo').textContent = accommodationName;
            qs('#modalAvailableBeds').textContent = currentAvailableBeds;
            qs('#modalTotalBeds').textContent = currentTotalBeds;

            // Populate beds dropdown - FIXED VERSION
            const bedsSelect = qs('#number_of_beds');
            if (bedsSelect) {
                bedsSelect.innerHTML = '<option value="">Select number of beds</option>';
                
                if (currentAvailableBeds > 0) {
                    // Create options from 1 up to available beds (max 10)
                    const maxBeds = Math.min(currentAvailableBeds, 10);
                    for (let i = 1; i <= maxBeds; i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        option.textContent = `${i} Bed${i > 1 ? 's' : ''}`;
                        bedsSelect.appendChild(option);
                    }
                    bedsSelect.disabled = false;
                } else {
                    // No beds available
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No beds available";
                    option.disabled = true;
                    bedsSelect.appendChild(option);
                    bedsSelect.disabled = true;
                }
            }

            // Update beds availability text
            const availabilityText = qs('#bedsAvailabilityText');
            if (availabilityText) {
                if (currentAvailableBeds > 0) {
                    availabilityText.innerHTML = `<i class="fas fa-check-circle me-1 text-success"></i> ${currentAvailableBeds} bed${currentAvailableBeds > 1 ? 's' : ''} available for booking`;
                    availabilityText.className = 'form-text text-success';
                } else {
                    availabilityText.innerHTML = `<i class="fas fa-times-circle me-1 text-danger"></i> No beds available for booking`;
                    availabilityText.className = 'form-text text-danger';
                }
            }

            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];

            if (qs('#check_in_date')) {
                qs('#check_in_date').value = today;
                qs('#check_in_date').min = today;
            }
            if (qs('#check_out_date')) {
                qs('#check_out_date').value = tomorrowStr;
                qs('#check_out_date').min = tomorrowStr;
            }

            // Update nights display
            updateNightsDisplay();

            // Auto-fill user data if logged in
            @if(auth()->check())
            const user = @json(auth()->user());
            if (user) {
                // Set service number
                if (user.service_number && qs('#service_number') && !qs('#service_number').value) {
                    qs('#service_number').value = user.service_number;
                }
                
                // Set rank
                if (user.rank && qs('#rank') && !qs('#rank').value) {
                    qs('#rank').value = user.rank;
                }
                
                // Set unit
                if (user.unit && qs('#unit') && !qs('#unit').value) {
                    qs('#unit').value = user.unit;
                }
                
                // Set branch
                if (user.branch && qs('#branch') && !qs('#branch').value) {
                    qs('#branch').value = user.branch;
                }
                
                // Set guest_name
                if (user.first_name && user.last_name && qs('#guest_name') && !qs('#guest_name').value) {
                    qs('#guest_name').value = `${user.first_name} ${user.last_name}`;
                }
                
                // Set guest_phone
                if (user.phone && qs('#guest_phone') && !qs('#guest_phone').value) {
                    qs('#guest_phone').value = user.phone;
                }
                
                // Set guest_email
                if (user.email && qs('#guest_email') && !qs('#guest_email').value) {
                    qs('#guest_email').value = user.email;
                }
            }
            @endif

            // ENABLE SUBMIT BUTTON BY DEFAULT
            if (qs('#submitBookingBtn')) {
                qs('#submitBookingBtn').disabled = false;
            }
            
            // Show the modal
            if (bookingModal) {
                bookingModal.show();
            }
        }
    });

    // Fix for view-details buttons - use event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.view-details-btn')) {
            const btn = e.target.closest('.view-details-btn');
            e.preventDefault();
            
            const imagesJson = btn.dataset.images || '[]';
            let images = [];
            try {
                images = JSON.parse(imagesJson);
            } catch (e) {
                images = [];
            }

            // Get accommodation details from the card
            const card = btn.closest('.accommodation-card');
            const name = card ? (card.querySelector('h5')?.textContent || '') : '';
            const location = card ? (card.querySelector('.img-overlay small')?.textContent || '') : '';
            const description = card ? (card.querySelector('.card-text')?.textContent || '') : '';
            const amenities = card ? (card.querySelector('.amenities-list')?.innerHTML || '') : '';
            const rating = card ? (card.querySelector('.rating')?.innerHTML || '') : '';
            const availableBeds = card?.dataset?.availableBeds || '0';
            const totalBeds = card?.dataset?.totalBeds || '0';
            const bedrooms = card?.dataset?.bedrooms || 'N/A';
            const bathrooms = card?.dataset?.bathrooms || 'N/A';
            const maxGuests = card?.dataset?.maxGuests || 'N/A';

            // Build images html
            let imagesHtml = '';
            const modalIdSafe = 'imagesCarousel';

            if (images.length === 0) {
                imagesHtml = `
                    <div class="text-center py-4">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No images available</p>
                    </div>
                `;
            } else if (images.length === 1) {
                imagesHtml = `<img src="${images[0]}" class="img-fluid rounded mb-3 w-100" alt="Accommodation Image" style="max-height: 400px; object-fit: cover;">`;
            } else {
                const items = images.map((img, idx) => {
                    return `<div class="carousel-item ${idx === 0 ? 'active' : ''}">
                        <img src="${img}" class="d-block w-100 rounded" alt="Accommodation Image ${idx+1}" style="height: 400px; object-fit: cover;">
                    </div>`;
                }).join('');

                const indicators = images.map((_, idx) => {
                    return `<button type="button" data-bs-target="#${modalIdSafe}" data-bs-slide-to="${idx}" 
                    class="${idx === 0 ? 'active' : ''}" aria-current="${idx === 0 ? 'true' : 'false'}" 
                    aria-label="Slide ${idx+1}"></button>`;
                }).join('');

                imagesHtml = `
                    <div id="${modalIdSafe}" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-indicators">${indicators}</div>
                        <div class="carousel-inner rounded">${items}</div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#${modalIdSafe}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#${modalIdSafe}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                `;
            }

            const html = `
                <div class="row">
                    <div class="col-lg-7">
                        ${imagesHtml}
                    </div>
                    <div class="col-lg-5">
                        <h3 class="mb-3">${escapeHtml(name)}</h3>
                        <p class="text-muted mb-3 d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            ${escapeHtml(location)}
                        </p>
                        
                        <!-- Bed Information -->
                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Bed Availability</h6>
                                    <p class="mb-0">
                                        <strong>${availableBeds}/${totalBeds}</strong> beds available
                                    </p>
                                </div>
                                <i class="fas fa-bed fa-2x text-primary"></i>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                            <div class="text-center">
                                <div class="fw-bold text-primary">${bedrooms}</div>
                                <small class="text-muted">Bedrooms</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold text-primary">${bathrooms}</div>
                                <small class="text-muted">Bathrooms</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold text-primary">${maxGuests}</div>
                                <small class="text-muted">Max Guests</small>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Description</h6>
                            <p class="text-muted">${escapeHtml(description)}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Rating & Reviews</h6>
                            <div class="d-flex align-items-center">
                                ${rating}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">Amenities & Features</h6>
                        <div class="amenities-grid">
                            ${amenities}
                        </div>
                    </div>
                </div>
            `;

            const body = qs('#detailsModalBody');
            if (body) {
                body.innerHTML = html;

                // Show Book button and wire it to open booking modal
                const detailsBookBtn = qs('#detailsBookBtn');
                if (detailsBookBtn) {
                    detailsBookBtn.style.display = 'none';

                    const accId = btn.dataset.accommodationId;
                    if (accId && parseInt(availableBeds) > 0) {
                        detailsBookBtn.style.display = 'inline-block';
                        detailsBookBtn.onclick = () => {
                            const bookButton = document.querySelector(`.book-now-btn[data-accommodation-id="${accId}"]`);
                            if (bookButton) {
                                bookButton.click();
                            }
                            if (detailsModal) {
                                detailsModal.hide();
                            }
                        };
                    }
                }
            }
            
            // Show the modal
            if (detailsModal) {
                detailsModal.show();
            }
        }
    });

    // Update nights display when dates change
    function updateNightsDisplay() {
        const checkinInput = qs('#check_in_date');
        const checkoutInput = qs('#check_out_date');
        const nightsDisplay = qs('#modalNights');
        const nightsSummary = qs('#modalNightsSummary');
        const bedsCount = qs('#modalBedsCount');
        const bedsSelect = qs('#number_of_beds');

        if (checkinInput && checkoutInput && nightsDisplay && nightsSummary) {
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);
            
            if (checkin && checkout && checkout > checkin) {
                const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
                nightsDisplay.textContent = nights;
                nightsSummary.textContent = nights;
            } else {
                nightsDisplay.textContent = '0';
                nightsSummary.textContent = '0';
            }
        }

        // Update beds count in summary
        if (bedsCount && bedsSelect && bedsSelect.value) {
            bedsCount.textContent = bedsSelect.value;
        } else if (bedsCount) {
            bedsCount.textContent = '0';
        }
    }

    // Set up event listeners for form fields
    const checkinInput = qs('#check_in_date');
    const checkoutInput = qs('#check_out_date');
    const bedsSelect = qs('#number_of_beds');

    if (checkinInput && checkoutInput) {
        checkinInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                if (checkoutInput) {
                    checkoutInput.min = nextDay.toISOString().split('T')[0];
                    if (!checkoutInput.value || new Date(checkoutInput.value) <= new Date(this.value)) {
                        checkoutInput.value = nextDay.toISOString().split('T')[0];
                    }
                }
            }
            updateNightsDisplay();
        });

        checkoutInput.addEventListener('change', updateNightsDisplay);
    }

    if (bedsSelect) {
        bedsSelect.addEventListener('change', updateNightsDisplay);
    }

    // Form submit validation - ONLY validate when form is submitted
    const bookingForm = qs('#bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const validation = validateFormOnSubmit();
            
            if (!validation.isValid) {
                e.preventDefault();
                // Show error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show mt-3';
                
                let errorHtml = `<i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">`;
                
                validation.errorMessages.forEach(msg => {
                    errorHtml += `<li>${msg}</li>`;
                });
                
                errorHtml += `</ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                
                errorAlert.innerHTML = errorHtml;
                
                // Remove any existing error alerts
                const existingAlerts = document.querySelectorAll('#bookingForm .alert-danger');
                existingAlerts.forEach(alert => alert.remove());
                
                // Insert at the top of modal body
                const modalBody = qs('.modal-body');
                if (modalBody) {
                    modalBody.insertBefore(errorAlert, modalBody.firstChild);
                    
                    // Auto-remove after 8 seconds
                    setTimeout(() => {
                        if (errorAlert.parentNode) {
                            errorAlert.remove();
                        }
                    }, 8000);
                }
            }
        });
    }

    function validateFormOnSubmit() {
        const requiredFields = [
            'service_number', 'rank', 'unit', 'branch', 
            'guest_name', 'guest_phone', 'guest_email',
            'number_of_beds', 'check_in_date', 'check_out_date', 'purpose'
        ];
        
        let isValid = true;
        let errorMessages = [];
        
        requiredFields.forEach(fieldId => {
            const field = qs(`#${fieldId}`);
            if (field && !field.value.trim()) {
                isValid = false;
                const fieldName = field.labels?.[0]?.textContent?.replace('*', '')?.trim() || fieldId;
                errorMessages.push(`${fieldName} is required`);
            }
        });
        
        // Additional validation for dates
        if (checkinInput && checkoutInput && checkinInput.value && checkoutInput.value) {
            const checkin = new Date(checkinInput.value);
            const checkout = new Date(checkoutInput.value);
            if (checkout <= checkin) {
                isValid = false;
                errorMessages.push('Check-out date must be after check-in date');
            }
        }
        
        return { isValid, errorMessages };
    }

    // Helper function to escape HTML
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return String(unsafe)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // Auto-hide bootstrap alerts after 5 seconds
    qsa('.alert').forEach(a => {
        setTimeout(() => {
            try {
                const bs = bootstrap.Alert.getOrCreateInstance(a);
                bs.close();
            } catch (e) {
                a.remove();
            }
        }, 5000);
    });

    // If there are validation errors and an old accommodation_id, open the booking modal
    @if ($errors->any() && old('accommodation_id'))
        (function() {
            const accommodationId = @json(old('accommodation_id'));
            const el = document.querySelector(`.book-now-btn[data-accommodation-id="${accommodationId}"]`);
            if (el) {
                setTimeout(() => {
                    el.click();
                }, 500);
            }
        })();
    @endif

});
</script>
@endsection 
