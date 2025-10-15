@extends('layouts.admin')

@section('page-title', 'All Bookings')

@section('content')
<div class="container-fluid">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Bookings Management</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Bookings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Quick Stats Cards --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $totalBookings ?? $bookings->total() }}</h4>
                                <span>Total Bookings</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-calendar display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $activeBookingsCount ?? $bookings->whereIn('status', ['confirmed', 'checked_in'])->count() }}</h4>
                                <span>Active Bookings</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-user-check display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $pendingBookingsCount ?? $bookings->where('status', 'pending')->count() }}</h4>
                                <span>Pending Approval</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-clock display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                @php
                                    $checkingOutToday = $checkingOutTodayCount ?? $bookings->filter(function($booking) {
                                        return $booking->check_out_date->isToday() && 
                                               in_array($booking->status, ['confirmed', 'checked_in']);
                                    })->count();
                                @endphp
                                <h4 class="mb-0">{{ $checkingOutToday }}</h4>
                                <span>Checking Out Today</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-log-out display-4 opacity-50"></i>
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
                        <h5 class="mb-0">Bookings List</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="feather icon-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">All Bookings</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}">Pending</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'confirmed']) }}">Confirmed</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'checked_in']) }}">Checked In</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'checked_out']) }}">Checked Out</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}">Cancelled</a></li>
                            </ul>
                            <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm ms-2">
                                <i class="feather icon-plus"></i> New Booking
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Guest</th>
                                        <th>Accommodation</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Time Left</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                        <tr class="{{ $booking->check_out_date->isToday() && in_array($booking->status, ['confirmed', 'checked_in']) ? 'table-warning' : '' }} {{ $booking->check_out_date->isPast() && in_array($booking->status, ['confirmed', 'checked_in']) ? 'table-danger' : '' }}">
                                            <td>
                                                <strong>#{{ $booking->id }}</strong>
                                                @if($booking->check_out_date->isToday() && in_array($booking->status, ['confirmed', 'checked_in']))
                                                    <span class="badge bg-warning ms-1" title="Checking out today">Today</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                                {{ substr($booking->guest_name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <strong>{{ $booking->guest_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $booking->guest_email }}</small>
                                                        <br>
                                                        <small class="text-muted">{{ $booking->guest_phone }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $booking->accommodation->name ?? 'N/A' }}</strong>
                                                @if($booking->accommodation)
                                                    <br>
                                                    <small class="text-muted">{{ $booking->accommodation->lodge_name }}</small>
                                                    <br>
                                                    <small class="badge bg-secondary">{{ $booking->number_of_beds }} bed(s)</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $booking->check_in_date->format('M d, Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $booking->check_in_date->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $booking->check_out_date->format('M d, Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $booking->check_out_date->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if(in_array($booking->status, ['confirmed', 'checked_in']))
                                                    @php
                                                        $now = now();
                                                        $checkOut = $booking->check_out_date;
                                                        $diff = $now->diff($checkOut);
                                                    @endphp
                                                    @if($checkOut->isPast())
                                                        <span class="badge bg-danger">Overdue</span>
                                                    @elseif($checkOut->isToday())
                                                        <span class="badge bg-warning">
                                                            <i class="feather icon-clock me-1"></i>
                                                            {{ $diff->h }}h {{ $diff->i }}m left
                                                        </span>
                                                    @elseif($diff->days == 1)
                                                        <span class="badge bg-info">1 day left</span>
                                                    @elseif($diff->days < 7)
                                                        <span class="badge bg-primary">{{ $diff->days }} days left</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $diff->days }} days left</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>GH¢{{ number_format($booking->total_amount, 2) }}</strong>
                                                <br>
                                                <small class="badge badge-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($booking->payment_status ?? 'pending') }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $booking->status_badge }}">
                                                    <i class="feather icon-{{ $booking->status === 'checked_in' ? 'user-check' : ($booking->status === 'confirmed' ? 'check-circle' : ($booking->status === 'pending' ? 'clock' : ($booking->status === 'checked_out' ? 'log-out' : 'x-circle'))) }} me-1"></i>
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                @if($booking->status === 'checked_in')
                                                    <br>
                                                    <small class="text-success">Currently staying</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    {{-- View Button --}}
                                                    <button class="btn btn-outline-info btn-view-booking" data-booking-id="{{ $booking->id }}" title="View Details">
                                                        <i class="feather icon-eye"></i>
                                                    </button>
                                                    
                                                    {{-- Edit Button with Dropdown --}}
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Edit">
                                                            <i class="feather icon-edit"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.bookings.edit', $booking->id) }}">
                                                                    <i class="feather icon-edit me-1"></i>Edit Details
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><h6 class="dropdown-header">Quick Status Update</h6></li>
                                                            <li>
                                                                <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="pending">
                                                                    <span class="badge badge-warning me-1">●</span>Mark as Pending
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="confirmed">
                                                                    <span class="badge badge-success me-1">●</span>Mark as Confirmed
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="checked_in">
                                                                    <span class="badge badge-info me-1">●</span>Mark as Checked In
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="checked_out">
                                                                    <span class="badge badge-secondary me-1">●</span>Mark as Checked Out
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="cancelled">
                                                                    <span class="badge badge-danger me-1">●</span>Mark as Cancelled
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $booking->id }})">
                                                                    <i class="feather icon-trash-2 me-1"></i>Delete Booking
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="feather icon-calendar display-1 text-muted"></i>
                                                    <h4 class="mt-3">No bookings found</h4>
                                                    <p class="text-muted">No bookings match your current filters.</p>
                                                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                                                        <i class="feather icon-plus me-1"></i>Create New Booking
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries
                            </div>
                            <div>
                                {{ $bookings->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Booking Details Modal ===== --}}
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details #<span id="modalBookingId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Guest Information</h6>
                        <p><strong>Name:</strong> <span id="modalGuestName"></span></p>
                        <p><strong>Email:</strong> <span id="modalGuestEmail"></span></p>
                        <p><strong>Phone:</strong> <span id="modalGuestPhone"></span></p>
                        <p><strong>Service Number:</strong> <span id="modalServiceNumber"></span></p>
                        <p><strong>Rank:</strong> <span id="modalRank"></span></p>
                        <p><strong>Unit:</strong> <span id="modalUnit"></span></p>
                        <p><strong>Branch:</strong> <span id="modalBranch"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Booking Information</h6>
                        <p><strong>Status:</strong> <span id="modalStatus" class="badge"></span></p>
                        <p><strong>Beds:</strong> <span id="modalBedsBooked"></span></p>
                        <p><strong>Check-in:</strong> <span id="modalCheckIn"></span></p>
                        <p><strong>Check-out:</strong> <span id="modalCheckOut"></span></p>
                        <p><strong>Actual Check-in:</strong> <span id="modalActualCheckIn"></span></p>
                        <p><strong>Actual Check-out:</strong> <span id="modalActualCheckOut"></span></p>
                        <p><strong>Nights:</strong> <span id="modalNights"></span></p>
                        <p><strong>Time Left:</strong> <span id="modalTimeLeft" class="badge"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Accommodation Details</h6>
                        <p><strong>Property:</strong> <span id="modalAccommodation"></span></p>
                        <p><strong>Lodge:</strong> <span id="modalLodge"></span></p>
                        <p><strong>Type:</strong> <span id="modalAccommodationType"></span></p>
                        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                        <p><strong>Beds Available:</strong> <span id="modalAvailableBeds"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Payment Details</h6>
                        <p><strong>Total Amount:</strong> GH¢<span id="modalTotalAmount"></span></p>
                        <p><strong>Payment Status:</strong> <span id="modalPaymentStatus" class="badge"></span></p>
                        <p><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                        <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                        <p><strong>Booked On:</strong> <span id="modalCreatedAt"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Special Requests</h6>
                        <p id="modalSpecialRequests" class="text-muted">No special requests</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                
                {{-- Quick Status Dropdown in Modal --}}
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="feather icon-edit me-1"></i>Update Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Change Status</h6></li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="pending">
                                <span class="badge badge-warning me-1">●</span>Mark as Pending
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="confirmed">
                                <span class="badge badge-success me-1">●</span>Mark as Confirmed
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="checked_in">
                                <span class="badge badge-info me-1">●</span>Mark as Checked In
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="checked_out">
                                <span class="badge badge-secondary me-1">●</span>Mark as Checked Out
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="cancelled">
                                <span class="badge badge-danger me-1">●</span>Mark as Cancelled
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" id="modalFullEditLink">
                                <i class="feather icon-edit me-1"></i>Full Edit
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Form --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Toast Container --}}
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>

{{-- ===== Bootstrap JS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl)
    });

    // Modal functionality
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
    
    // Store current booking ID for modal updates
    let currentBookingId = null;

    // Add click event listeners to all view buttons
    document.querySelectorAll('.btn-view-booking').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingId = this.getAttribute('data-booking-id');
            loadBookingDetails(bookingId);
        });
    });

    function loadBookingDetails(bookingId) {
        // Show loading state
        document.getElementById('modalBookingId').textContent = bookingId;
        document.getElementById('modalGuestName').textContent = 'Loading...';
        
        // Fetch booking details via AJAX
        fetch(`/admin/bookings/${bookingId}/details`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    populateModal(data.booking);
                    currentBookingId = bookingId;
                    bookingModal.show();
                } else {
                    showToast('Error', data.message || 'Failed to load booking details', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'Error loading booking details', 'error');
            });
    }

    function populateModal(booking) {
        // Basic booking info
        document.getElementById('modalBookingId').textContent = booking.id;
        document.getElementById('modalGuestName').textContent = booking.guest_name || 'N/A';
        document.getElementById('modalGuestEmail').textContent = booking.guest_email || 'N/A';
        document.getElementById('modalGuestPhone').textContent = booking.guest_phone || 'N/A';
        document.getElementById('modalServiceNumber').textContent = booking.service_number || 'N/A';
        document.getElementById('modalRank').textContent = booking.rank || 'N/A';
        document.getElementById('modalUnit').textContent = booking.unit || 'N/A';
        document.getElementById('modalBranch').textContent = booking.branch || 'N/A';
        document.getElementById('modalPurpose').textContent = booking.purpose || 'N/A';
        
        // Dates
        document.getElementById('modalCheckIn').textContent = formatDate(booking.check_in_date);
        document.getElementById('modalCheckOut').textContent = formatDate(booking.check_out_date);
        document.getElementById('modalActualCheckIn').textContent = booking.actual_check_in ? formatDate(booking.actual_check_in) : 'Not checked in yet';
        document.getElementById('modalActualCheckOut').textContent = booking.actual_check_out ? formatDate(booking.actual_check_out) : 'Not checked out yet';
        document.getElementById('modalNights').textContent = calculateNights(booking.check_in_date, booking.check_out_date);
        document.getElementById('modalBedsBooked').textContent = booking.number_of_beds || 'N/A';
        
        // Time left calculation
        const timeLeftBadge = document.getElementById('modalTimeLeft');
        if (['confirmed', 'checked_in'].includes(booking.status)) {
            const now = new Date();
            const checkOut = new Date(booking.check_out_date);
            const diff = checkOut - now;
            
            if (diff < 0) {
                timeLeftBadge.textContent = 'Overdue';
                timeLeftBadge.className = 'badge bg-danger';
            } else if (diff < 24 * 60 * 60 * 1000) { // Less than 24 hours
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                timeLeftBadge.textContent = `${hours}h ${minutes}m left`;
                timeLeftBadge.className = 'badge bg-warning';
            } else {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                timeLeftBadge.textContent = `${days} day${days !== 1 ? 's' : ''} left`;
                timeLeftBadge.className = days < 7 ? 'badge bg-primary' : 'badge bg-secondary';
            }
        } else {
            timeLeftBadge.textContent = 'N/A';
            timeLeftBadge.className = 'badge bg-secondary';
        }
        
        // Status with badge
        const statusBadge = document.getElementById('modalStatus');
        statusBadge.textContent = booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'N/A';
        statusBadge.className = `badge badge-${getStatusBadgeClass(booking.status)}`;
        
        // Accommodation details
        document.getElementById('modalAccommodation').textContent = booking.accommodation?.name || 'N/A';
        document.getElementById('modalLodge').textContent = booking.accommodation?.lodge_name || 'N/A';
        document.getElementById('modalAccommodationType').textContent = booking.accommodation?.type || 'N/A';
        document.getElementById('modalLocation').textContent = booking.accommodation?.location || 'N/A';
        document.getElementById('modalAvailableBeds').textContent = booking.accommodation ? 
            `${booking.accommodation.available_beds}/${booking.accommodation.total_beds}` : 'N/A';
        
        // Payment info
        document.getElementById('modalTotalAmount').textContent = parseFloat(booking.total_amount || 0).toFixed(2);
        document.getElementById('modalPaymentStatus').textContent = booking.payment_status || 'N/A';
        document.getElementById('modalPaymentStatus').className = `badge badge-${getPaymentStatusBadgeClass(booking.payment_status)}`;
        document.getElementById('modalPaymentMethod').textContent = booking.payment_method || 'N/A';
        document.getElementById('modalCreatedAt').textContent = formatDate(booking.created_at);
        
        // Special requests
        const specialRequests = document.getElementById('modalSpecialRequests');
        if (booking.special_requests) {
            specialRequests.textContent = booking.special_requests;
            specialRequests.className = '';
        } else {
            specialRequests.textContent = 'No special requests';
            specialRequests.className = 'text-muted';
        }
        
        // Edit link
        document.getElementById('modalFullEditLink').href = `/admin/bookings/${booking.id}/edit`;
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            return 'Invalid date';
        }
    }

    function calculateNights(checkIn, checkOut) {
        if (!checkIn || !checkOut) return 'N/A';
        try {
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const diffTime = Math.abs(checkOutDate - checkInDate);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        } catch (e) {
            return 'N/A';
        }
    }

    function getStatusBadgeClass(status) {
        const statusClasses = {
            'confirmed': 'success',
            'pending': 'warning',
            'checked_in': 'info',
            'checked_out': 'secondary',
            'cancelled': 'danger'
        };
        return statusClasses[status] || 'secondary';
    }

    function getPaymentStatusBadgeClass(paymentStatus) {
        const paymentClasses = {
            'paid': 'success',
            'pending': 'warning',
            'failed': 'danger',
            'refunded': 'info'
        };
        return paymentClasses[paymentStatus] || 'secondary';
    }

    // Quick status update functionality
    document.querySelectorAll('.quick-status-update').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingId = this.getAttribute('data-booking-id');
            const newStatus = this.getAttribute('data-status');
            
            updateBookingStatus(bookingId, newStatus);
        });
    });

    // Modal quick status update
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-quick-status-update')) {
            e.preventDefault();
            const newStatus = e.target.getAttribute('data-status');
            
            if (currentBookingId) {
                updateBookingStatus(currentBookingId, newStatus);
            } else {
                showToast('Error', 'No booking selected', 'error');
            }
        }
    });

    function updateBookingStatus(bookingId, newStatus) {
        if (!confirm(`Are you sure you want to update the booking status to "${newStatus}"?`)) {
            return;
        }

        // Show loading state
        const button = document.querySelector(`.quick-status-update[data-booking-id="${bookingId}"][data-status="${newStatus}"]`) || 
                       document.querySelector('.modal-quick-status-update[data-status="' + newStatus + '"]');
        let originalText = '';
        if (button) {
            originalText = button.innerHTML;
            button.innerHTML = '<i class="feather icon-loader spin me-1"></i>Updating...';
        }

        // Use FormData for better Laravel compatibility
        const formData = new FormData();
        formData.append('status', newStatus);
        formData.append('_token', '{{ csrf_token() }}');

        // Send AJAX request
        fetch(`/admin/bookings/${bookingId}/quick-status-update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    let errorMessage = `HTTP error! status: ${response.status}`;
                    try {
                        const errorData = JSON.parse(text);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        if (text) errorMessage = text;
                    }
                    throw new Error(errorMessage);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                showToast('Success', `Booking status updated to ${newStatus}`, 'success');
                
                // Close modal if open
                if (bookingModal && bookingModal._element.classList.contains('show')) {
                    bookingModal.hide();
                }
                
                // Close dropdowns
                const dropdowns = document.querySelectorAll('.dropdown-menu.show');
                dropdowns.forEach(dropdown => {
                    const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                    if (dropdownInstance) {
                        dropdownInstance.hide();
                    }
                });
                
                // Reload page after a short delay to reflect changes
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast('Error', data.message || 'Failed to update status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Error updating status: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button text
            if (button && originalText) {
                button.innerHTML = originalText;
            }
        });
    }

    // Delete booking function
    window.confirmDelete = function(bookingId) {
        if (confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/bookings/${bookingId}`;
            form.submit();
        }
    };

    // Toast notification function
    function showToast(title, message, type = 'info') {
        const toastContainer = document.getElementById('toast-container');
        const toastId = 'toast-' + Date.now();
        const bgColor = type === 'success' ? 'bg-success' : 
                       type === 'error' ? 'bg-danger' : 
                       type === 'warning' ? 'bg-warning' : 'bg-info';
        
        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 5000
        });
        toast.show();
        
        // Remove toast from DOM after hide
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    // Add CSS for spinning loader
    const style = document.createElement('style');
    style.textContent = `
        .feather.icon-loader.spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .empty-state {
            text-align: center;
            padding: 2rem 0;
        }
        .avatar-xs {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table > :not(caption) > * > * {
            vertical-align: middle;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-info { background-color: #17a2b8; }
        .badge-secondary { background-color: #6c757d; }
        .badge-danger { background-color: #dc3545; }
    `;
    document.head.appendChild(style);
});
</script>
@endsection