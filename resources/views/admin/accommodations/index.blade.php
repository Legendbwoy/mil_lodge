@extends('layouts.admin')

@section('page-title', 'Show Accommodations')

@section('content')
<div class="container-fluid">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">All Accommodations</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Accommodations</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $totalBeds }}</h4>
                                <p class="mb-0">Total Beds</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-layers fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $availableBeds }}</h4>
                                <p class="mb-0">Available Beds</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-check-circle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $occupiedBeds }}</h4>
                                <p class="mb-0">Occupied Beds</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-user fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Accommodations List</h5>
                        <a href="{{ route('admin.accommodations.create') }}" class="btn btn-primary btn-sm">
                            <i class="feather icon-plus"></i> Add New Accommodation
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Bed Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($accommodations as $accommodation)
                                        <tr class="accommodation-row" 
                                            data-id="{{ $accommodation->id }}"
                                            data-name="{{ $accommodation->name }}"
                                            data-location="{{ $accommodation->location }}"
                                            data-type="{{ $accommodation->type }}"
                                            data-status="{{ $accommodation->is_available ? 'Available' : 'Unavailable' }}"
                                            data-featured="{{ $accommodation->is_featured }}"
                                            data-description="{{ $accommodation->description }}"
                                            data-bedrooms="{{ $accommodation->bedrooms }}"
                                            data-bathrooms="{{ $accommodation->bathrooms }}"
                                            data-max-guests="{{ $accommodation->max_guests }}"
                                            data-total-beds="{{ $accommodation->total_beds }}"
                                            data-available-beds="{{ $accommodation->available_beds }}"
                                            data-occupied-beds="{{ $accommodation->total_beds - $accommodation->available_beds }}"
                                            data-image="{{ $accommodation->featured_image ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80' }}">
                                            <td>{{ $accommodation->id }}</td>
                                            <td>
                                                <img src="{{ $accommodation->featured_image ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80' }}" 
                                                     alt="{{ $accommodation->name }}" 
                                                     style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            </td>
                                            <td>
                                                <strong>{{ $accommodation->name }}</strong>
                                                @if($accommodation->is_featured)
                                                    <span class="badge badge-warning ml-1">Featured</span>
                                                @endif
                                            </td>
                                            <td>{{ $accommodation->location }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($accommodation->type) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $accommodation->is_available ? 'success' : 'danger' }}">
                                                    {{ $accommodation->is_available ? 'Available' : 'Unavailable' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="bed-status-indicator">
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress" style="height: 8px; width: 80px; margin-right: 8px;">
                                                            <div class="progress-bar bg-success" 
                                                                 style="width: {{ $accommodation->total_beds > 0 ? ($accommodation->available_beds / $accommodation->total_beds) * 100 : 0 }}%">
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ $accommodation->available_beds }}/{{ $accommodation->total_beds }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.accommodations.edit', $accommodation->id ) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="feather icon-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info view-details-btn" 
                                                            title="View Details">
                                                        <i class="feather icon-eye"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-accommodation-btn" 
                                                            title="Delete"
                                                            data-id="{{ $accommodation->id }}"
                                                            data-name="{{ $accommodation->name }}">
                                                        <i class="feather icon-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No accommodations found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $accommodations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accommodation Details Modal -->
<div class="modal fade" id="accommodationDetailsModal" tabindex="-1" aria-labelledby="accommodationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="accommodationDetailsModalLabel">
                    <i class="feather icon-info mr-2"></i>
                    Accommodation Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="accommodation-image-container mb-4">
                            <img id="modalAccommodationImage" src="" alt="Accommodation Image" 
                                 class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="accommodation-basic-info">
                            <h4 id="modalAccommodationName" class="text-primary mb-2"></h4>
                            <p id="modalAccommodationLocation" class="text-muted mb-3">
                                <i class="feather icon-map-pin mr-2"></i>
                                <span id="modalLocationText"></span>
                            </p>
                            
                            <div class="accommodation-meta mb-4">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="meta-item">
                                            <div class="meta-value text-primary font-weight-bold" id="modalBedrooms">0</div>
                                            <div class="meta-label text-muted small">Bedrooms</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="meta-item">
                                            <div class="meta-value text-primary font-weight-bold" id="modalBathrooms">0</div>
                                            <div class="meta-label text-muted small">Bathrooms</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="meta-item">
                                            <div class="meta-value text-primary font-weight-bold" id="modalMaxGuests">0</div>
                                            <div class="meta-label text-muted small">Max Guests</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accommodation-status mb-3">
                                <span class="badge badge-info mr-2" id="modalTypeBadge"></span>
                                <span class="badge" id="modalStatusBadge"></span>
                                <span class="badge badge-warning ml-2" id="modalFeaturedBadge" style="display: none;">
                                    <i class="feather icon-star mr-1"></i>Featured
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Description</h6>
                        <p id="modalDescription" class="text-muted"></p>
                    </div>
                </div>
                
                <!-- Bed Status Breakdown -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Bed Status Breakdown</h6>
                        <div class="bed-status-breakdown">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-success text-white text-center">
                                        <div class="card-body py-4">
                                            <i class="feather icon-check-circle fa-2x mb-2"></i>
                                            <h4 id="modalAvailableBeds" class="mb-1">0</h4>
                                            <p class="mb-0">Beds Available</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-warning text-white text-center">
                                        <div class="card-body py-4">
                                            <i class="feather icon-user fa-2x mb-2"></i>
                                            <h4 id="modalOccupiedBeds" class="mb-1">0</h4>
                                            <p class="mb-0">Beds Occupied</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-primary text-white text-center">
                                        <div class="card-body py-4">
                                            <i class="feather icon-layers fa-2x mb-2"></i>
                                            <h4 id="modalTotalBeds" class="mb-1">0</h4>
                                            <p class="mb-0">Total Beds</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bed Occupancy Progress -->
                            <div class="bed-occupancy-progress mt-4">
                                <h6 class="mb-3">Bed Occupancy Rate</h6>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" 
                                         id="modalAvailableProgress" 
                                         style="width: 0%"
                                         data-toggle="tooltip" 
                                         title="Available Beds">
                                        <span class="progress-text">Available: <span id="modalAvailablePercent">0%</span></span>
                                    </div>
                                    <div class="progress-bar bg-warning" 
                                         id="modalOccupiedProgress" 
                                         style="width: 0%"
                                         data-toggle="tooltip" 
                                         title="Occupied Beds">
                                        <span class="progress-text">Occupied: <span id="modalOccupiedPercent">0%</span></span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted" id="modalOccupancyText">Occupancy: 0%</small>
                                    <small class="text-muted" id="modalAvailabilityText">Availability: 0%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Quick Actions</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-primary btn-sm" id="modalEditBtn">
                                <i class="feather icon-edit mr-1"></i> Edit Accommodation
                            </button>
                            <button class="btn btn-outline-info btn-sm" id="modalViewBookingsBtn">
                                <i class="feather icon-calendar mr-1"></i> View Bookings
                            </button>
                            <button class="btn btn-outline-success btn-sm" id="modalUpdateStatusBtn">
                                <i class="feather icon-refresh-cw mr-1"></i> Update Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteAccommodationForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('styles')
<style>
.accommodation-row {
    cursor: pointer !important;
    transition: all 0.2s ease;
}

.accommodation-row:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.bed-status-indicator .progress {
    background-color: #e9ecef;
}

.bed-status-breakdown .card {
    border: none;
    border-radius: 8px;
    transition: transform 0.2s ease;
}

.bed-status-breakdown .card:hover {
    transform: translateY(-2px);
}

.bed-occupancy-progress .progress {
    border-radius: 6px;
    overflow: hidden;
}

.bed-occupancy-progress .progress-bar {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}

.progress-text {
    position: absolute;
    color: white;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.meta-item {
    padding: 10px;
}

.meta-value {
    font-size: 1.5rem;
}

.meta-label {
    font-size: 0.8rem;
    margin-top: 5px;
}

.accommodation-image-container {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-group .btn {
    margin-right: 2px;
}

/* Summary Cards */
.card.bg-primary,
.card.bg-success,
.card.bg-warning {
    border: none;
    border-radius: 8px;
    transition: transform 0.2s ease;
}

.card.bg-primary:hover,
.card.bg-success:hover,
.card.bg-warning:hover {
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .accommodation-row {
        font-size: 0.9rem;
    }
    
    .bed-status-breakdown .card-body {
        padding: 1rem;
    }
    
    .meta-value {
        font-size: 1.2rem;
    }
}
</style>
@endsection

@section('scripts')
<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle row clicks
    function handleRowClick(event) {
        const row = event.currentTarget;
        const accommodationId = row.dataset.id;
        
        // Populate modal with data
        document.getElementById('modalAccommodationName').textContent = row.dataset.name;
        document.getElementById('modalLocationText').textContent = row.dataset.location;
        document.getElementById('modalAccommodationImage').src = row.dataset.image;
        document.getElementById('modalTypeBadge').textContent = row.dataset.type.charAt(0).toUpperCase() + row.dataset.type.slice(1);
        document.getElementById('modalStatusBadge').textContent = row.dataset.status;
        
        // Set status badge color
        const statusBadge = document.getElementById('modalStatusBadge');
        statusBadge.className = 'badge ' + (row.dataset.status === 'Available' ? 'badge-success' : 'badge-danger');
        
        document.getElementById('modalDescription').textContent = row.dataset.description || 'No description available.';
        document.getElementById('modalBedrooms').textContent = row.dataset.bedrooms || '0';
        document.getElementById('modalBathrooms').textContent = row.dataset.bathrooms || '0';
        document.getElementById('modalMaxGuests').textContent = row.dataset.maxGuests || '0';
        
        // Show/hide featured badge
        const featuredBadge = document.getElementById('modalFeaturedBadge');
        if (row.dataset.featured === '1') {
            featuredBadge.style.display = 'inline-block';
        } else {
            featuredBadge.style.display = 'none';
        }
        
        // Bed status data
        const totalBeds = parseInt(row.dataset.totalBeds) || 0;
        const availableBeds = parseInt(row.dataset.availableBeds) || 0;
        const occupiedBeds = parseInt(row.dataset.occupiedBeds) || 0;
        
        document.getElementById('modalTotalBeds').textContent = totalBeds;
        document.getElementById('modalAvailableBeds').textContent = availableBeds;
        document.getElementById('modalOccupiedBeds').textContent = occupiedBeds;
        
        // Calculate percentages
        const availablePercent = totalBeds > 0 ? (availableBeds / totalBeds) * 100 : 0;
        const occupiedPercent = totalBeds > 0 ? (occupiedBeds / totalBeds) * 100 : 0;
        
        // Update progress bars
        document.getElementById('modalAvailableProgress').style.width = `${availablePercent}%`;
        document.getElementById('modalOccupiedProgress').style.width = `${occupiedPercent}%`;
        
        // Update percentage text
        document.getElementById('modalAvailablePercent').textContent = `${Math.round(availablePercent)}%`;
        document.getElementById('modalOccupiedPercent').textContent = `${Math.round(occupiedPercent)}%`;
        
        // Update occupancy text
        document.getElementById('modalOccupancyText').textContent = `Occupancy: ${Math.round(occupiedPercent)}%`;
        document.getElementById('modalAvailabilityText').textContent = `Availability: ${Math.round(availablePercent)}%`;
        
        // Set up action buttons
        document.getElementById('modalEditBtn').onclick = function() {
            window.location.href = `/admin/accommodations/${accommodationId}/edit`;
        };
        
        document.getElementById('modalViewBookingsBtn').onclick = function() {
            window.location.href = `/admin/bookings?accommodation=${accommodationId}`;
        };
        
        document.getElementById('modalUpdateStatusBtn').onclick = function() {
            // Implement status update logic here
            Swal.fire({
                title: 'Update Status',
                text: `Update status for ${row.dataset.name}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel'
            });
        };
        
        // Show the modal using Bootstrap
        $('#accommodationDetailsModal').modal('show');
    }
    
    // Add click event listeners to all accommodation rows
    document.querySelectorAll('.accommodation-row').forEach(row => {
        row.addEventListener('click', handleRowClick);
    });
    
    // Prevent view details button from triggering row click
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            const row = this.closest('.accommodation-row');
            handleRowClick({ currentTarget: row });
        });
    });
    
    // Delete accommodation with SweetAlert confirmation
    document.querySelectorAll('.delete-accommodation-btn').forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            
            const accommodationId = this.dataset.id;
            const accommodationName = this.dataset.name;
            
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${accommodationName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set up the delete form and submit it
                    const form = document.getElementById('deleteAccommodationForm');
                    form.action = `/admin/accommodations/${accommodationId}`;
                    form.submit();
                }
            });
        });
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection