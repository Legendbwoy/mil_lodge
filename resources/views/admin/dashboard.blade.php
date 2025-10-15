@extends('layouts.admin')

@section('page-title', 'Dashboard Overview')

@section('content')
<div class="container-fluid">

    {{-- ===== Notifications Alert ===== --}}
    @if(count($checkoutNotifications) > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h6 class="alert-heading"><i class="feather icon-bell me-2"></i>Check-out Notifications</h6>
        <ul class="mb-0">
            @foreach($checkoutNotifications as $notification)
                <li>{{ $notification['message'] }} ({{ $notification['time_remaining'] }})</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- ===== Top Metric Cards ===== --}}
    <div class="row mb-4">
        <div class="col-xl-15 col-md-4 col-sm-6 mb-3">
            <div class="card bg-c-green text-white widget-visitor-card">
                <div class="card-body text-center">
                    <h2 class="text-white">{{ $totalUsers }}</h2>
                    <h6 class="text-white">Registered Users</h6>
                    <i class="feather icon-user-plus"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-15 col-md-4 col-sm-6 mb-3">
            <div class="card bg-c-blue text-white widget-visitor-card">
                <div class="card-body text-center">
                    <h2 class="text-white">{{ $totalBookings }}</h2>
                    <h6 class="text-white">Total Bookings</h6>
                    <i class="feather icon-calendar"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-15 col-md-4 col-sm-6 mb-3">
            <div class="card bg-c-yellow text-white widget-visitor-card">
                <div class="card-body text-center">
                    <h2 class="text-white">{{ $totalAccommodations }}</h2>
                    <h6 class="text-white">Rooms</h6>
                    <i class="feather icon-home"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-15 col-md-4 col-sm-6 mb-3">
            <div class="card bg-c-purple text-white widget-visitor-card">
                <div class="card-body text-center">
                    <h2 class="text-white">{{ $activeBookings }}</h2>
                    <h6 class="text-white">Active Bookings</h6>
                    <i class="feather icon-user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-15 col-md-4 col-sm-6 mb-3">
            <div class="card bg-c-red text-white widget-visitor-card">
                <div class="card-body text-center">
                    <h4 class="text-white">{{ number_format($totalReports) }}</h4>
                    <h6 class="text-white">Total Reports</h6>
                    <i class="feather icon-alert-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Analytics Overview ===== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="feather icon-bar-chart-2 me-1"></i> Monthly Performance Overview</h5>
                    <a href="{{ route('admin.analytics') }}" class="btn btn-sm btn-primary">
                        <i class="feather icon-bar-chart me-1"></i>View Full Analytics
                    </a>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $bookingsThisMonth }}</h4>
                                <small class="text-muted">This Month Bookings</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">{{ $monthlyOccupancy }}%</h4>
                                <small class="text-muted">Occupancy Rate</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1">{{ $activeGuests }}</h4>
                                <small class="text-muted">Active Guests</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1">{{ $monthlyGrowth }}%</h4>
                                <small class="text-muted">Growth Rate</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-danger mb-1">{{ $reportsThisMonth }}</h4>
                                <small class="text-muted">Monthly Reports</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-secondary mb-1">{{ $newUsersThisMonth }}</h4>
                                <small class="text-muted">New Users</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Quick Stats & Actions ===== --}}
    <div class="row mb-4">
        <!-- System Overview -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-activity me-1"></i> System Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="mb-3">Booking Status</h6>
                            @foreach (['confirmed'=>'success','pending'=>'warning','checked_in'=>'info','checked_out'=>'secondary','cancelled'=>'danger'] as $status=>$color)
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2 text-capitalize" style="width:90px;">{{ $status }}</span>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar bg-{{ $color }}" 
                                             style="width: {{ $bookingStatusPercentages[$status] ?? 0 }}%">
                                        </div>
                                    </div>
                                    <span class="ms-2">{{ $bookingStatusPercentages[$status] ?? 0 }}%</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="mb-3">Accommodation Status</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Available Rooms</span>
                                <span class="fw-bold">{{ $availableAccommodations }} ({{ $accommodationAvailability }}%)</span>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" style="width: {{ $accommodationAvailability }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Occupied Rooms</span>
                                <span class="fw-bold">{{ $occupiedAccommodations }} ({{ 100 - $accommodationAvailability }}%)</span>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-warning" style="width: {{ 100 - $accommodationAvailability }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total Beds</span>
                                <span class="fw-bold">{{ $totalBeds }} ({{ $availableBeds }} available)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-bolt me-1"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.accommodations.create') }}" class="btn btn-primary w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="feather icon-plus mb-2" style="font-size: 24px;"></i>
                                <span>Add Room</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.bookings.create') }}" class="btn btn-success w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="feather icon-plus mb-2" style="font-size: 24px;"></i>
                                <span>Create Booking</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-info w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="feather icon-user-plus mb-2" style="font-size: 24px;"></i>
                                <span>Add User</span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.reports') }}" class="btn btn-warning w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="feather icon-file-text mb-2" style="font-size: 24px;"></i>
                                <span>View Reports</span>
                            </a>
                        </div>
                    </div>

                    <!-- Lodge Breakdown -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Lodge Breakdown</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6>Akafia Lodge</h6>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Available:</span>
                                        <span class="fw-bold">{{ $akafiaStats->available ?? 0 }}/{{ $akafiaStats->total ?? 0 }}</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ $akafiaStats->total > 0 ? (($akafiaStats->available ?? 0) / $akafiaStats->total) * 100 : 0 }}%"></div>
                                    </div>
                                    <small class="text-muted">
                                        Occupied: {{ $akafiaStats->occupied ?? 0 }}
                                    </small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>Oppong Peprah Lodge</h6>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Available:</span>
                                        <span class="fw-bold">{{ $oppongStats->available ?? 0 }}/{{ $oppongStats->total ?? 0 }}</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ $oppongStats->total > 0 ? (($oppongStats->available ?? 0) / $oppongStats->total) * 100 : 0 }}%"></div>
                                    </div>
                                    <small class="text-muted">
                                        Occupied: {{ $oppongStats->occupied ?? 0 }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Recent Activity ===== --}}
    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="feather icon-clock me-1"></i> Recent Bookings</h5>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Guest</th>
                                    <th>Property</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($booking->guest_name, 15) }}</div>
                                        <small class="text-muted">#{{ $booking->id }}</small>
                                    </td>
                                    <td>{{ Str::limit($booking->accommodation->name ?? 'N/A', 15) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $booking->status_badge }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info btn-view-booking" data-booking-id="{{ $booking->id }}">
                                            <i class="feather icon-eye"></i>
                                        </button>
                                        {{-- Quick Status Dropdown --}}
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="feather icon-edit-2"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><h6 class="dropdown-header">Quick Status</h6></li>
                                                <li>
                                                    <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="pending">
                                                        <span class="badge badge-warning me-1">●</span>Pending
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="confirmed">
                                                        <span class="badge badge-success me-1">●</span>Confirmed
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="checked_in">
                                                        <span class="badge badge-info me-1">●</span>Check In
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="checked_out">
                                                        <span class="badge badge-secondary me-1">●</span>Check Out
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="cancelled">
                                                        <span class="badge badge-danger me-1">●</span>Cancelled
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-3">No recent bookings found</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="feather icon-alert-triangle me-1"></i> Recent Reports</h5>
                    <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Priority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($recentReports as $report)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($report->title, 20) }}</div>
                                        <small class="text-muted">#{{ $report->id }}</small>
                                    </td>
                                    <td>{{ $report->report_type_label }}</td>
                                    <td>
                                        <span class="badge badge-{{ $report->priority_badge }}">
                                            {{ ucfirst($report->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info btn-view-report" data-report-id="{{ $report->id }}">
                                            <i class="feather icon-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-3">No recent reports found</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== Modals and Scripts ===== --}}

{{-- Booking Details Modal --}}
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
                        <p><strong>Beds Booked:</strong> <span id="modalBedsBooked"></span></p>
                        <p><strong>Check-in Date:</strong> <span id="modalCheckIn"></span></p>
                        <p><strong>Check-out Date:</strong> <span id="modalCheckOut"></span></p>
                        <p><strong>Actual Check-in:</strong> <span id="modalActualCheckIn"></span></p>
                        <p><strong>Actual Check-out:</strong> <span id="modalActualCheckOut"></span></p>
                        <p><strong>Nights:</strong> <span id="modalNights"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Accommodation Details</h6>
                        <p><strong>Property:</strong> <span id="modalAccommodation"></span></p>
                        <p><strong>Lodge:</strong> <span id="modalLodge"></span></p>
                        <p><strong>Type:</strong> <span id="modalAccommodationType"></span></p>
                        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                        <p><strong>Beds Booked:</strong> <span id="modalBeds"></span></p>
                        <p><strong>Beds Available:</strong> <span id="modalAvailableBeds"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Booking Details</h6>
                        <p><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
                        <p><strong>Special Requests:</strong> <span id="modalSpecialRequests"></span></p>
                        <p><strong>Booked On:</strong> <span id="modalCreatedAt"></span></p>
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
                        <li>
                            <a class="dropdown-item text-danger" href="#" id="modalDeleteLink">
                                <i class="feather icon-trash-2 me-1"></i>Delete Booking
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Report Details Modal --}}
<div class="modal fade" id="reportDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Details #<span id="modalReportId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Report Information</h6>
                        <p><strong>Title:</strong> <span id="modalReportTitle"></span></p>
                        <p><strong>Type:</strong> <span id="modalReportType"></span></p>
                        <p><strong>Location:</strong> <span id="modalReportLocation"></span></p>
                        <p><strong>Priority:</strong> <span id="modalReportPriority" class="badge"></span></p>
                        <p><strong>Status:</strong> <span id="modalReportStatus" class="badge"></span></p>
                        <p><strong>Submitted:</strong> <span id="modalReportCreatedAt"></span></p>
                        <p><strong>Resolved:</strong> <span id="modalReportResolvedAt"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Contact Information</h6>
                        <p><strong>Submitted By:</strong> <span id="modalReportUser"></span></p>
                        <p><strong>Email:</strong> <span id="modalReportUserEmail"></span></p>
                        <p><strong>Accommodation:</strong> <span id="modalReportAccommodation"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Description</h6>
                        <p id="modalReportDescription" class="bg-light p-3 rounded"></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Admin Notes</h6>
                        <p id="modalReportAdminNotes" class="bg-light p-3 rounded">
                            <span class="text-muted" id="modalReportNoNotes">No admin notes available</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                
                {{-- Report Status Dropdown --}}
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="feather icon-edit me-1"></i>Update Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Change Status</h6></li>
                        <li>
                            <a class="dropdown-item report-status-update-modal" href="#" data-status="pending">
                                <span class="badge badge-warning me-1">●</span>Mark as Pending
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item report-status-update-modal" href="#" data-status="in_progress">
                                <span class="badge badge-info me-1">●</span>Mark as In Progress
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item report-status-update-modal" href="#" data-status="resolved">
                                <span class="badge badge-success me-1">●</span>Mark as Resolved
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item report-status-update-modal" href="#" data-status="cancelled">
                                <span class="badge badge-secondary me-1">●</span>Mark as Cancelled
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="feather icon-alert-triangle me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this booking? This action cannot be undone and will free up the beds for new bookings.</p>
                <p class="text-muted"><small>This action is permanent and cannot be recovered.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete Booking</button>
            </div>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>

{{-- Delete Confirmation Form --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- JavaScript --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap modals
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
    const reportModal = new bootstrap.Modal(document.getElementById('reportDetailsModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));

    // Store modals globally for access
    window.bookingModal = bookingModal;
    window.reportModal = reportModal;

    let currentBookingId = null;
    let currentReportId = null;

    // ===== BOOKING EVENT HANDLERS =====

    // View Booking Details
    document.querySelectorAll('.btn-view-booking').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingId = this.getAttribute('data-booking-id');
            loadBookingDetails(bookingId);
        });
    });

    // Quick Status Update from table dropdown
    document.querySelectorAll('.quick-status-update').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const bookingId = this.getAttribute('data-booking-id');
            const newStatus = this.getAttribute('data-status');
            updateBookingStatus(bookingId, newStatus);
        });
    });

    // Modal Quick Status Update
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-quick-status-update')) {
            e.preventDefault();
            e.stopPropagation();
            const newStatus = e.target.getAttribute('data-status');
            if (currentBookingId) {
                updateBookingStatus(currentBookingId, newStatus);
            } else {
                showToast('Error', 'No booking selected', 'error');
            }
        }
    });

    // ===== REPORT EVENT HANDLERS =====

    // View Report Details
    document.querySelectorAll('.btn-view-report').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const reportId = this.getAttribute('data-report-id');
            loadReportDetails(reportId);
        });
    });

    // Report Status Update from modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('report-status-update-modal')) {
            e.preventDefault();
            e.stopPropagation();
            const newStatus = e.target.getAttribute('data-status');
            if (currentReportId) {
                updateReportStatus(currentReportId, newStatus);
            } else {
                showToast('Error', 'No report selected', 'error');
            }
        }
    });

    // ===== CORE FUNCTIONS =====

    // Load Booking Details
    function loadBookingDetails(bookingId) {
        showLoading('Loading booking details...');
        
        fetch(`/admin/bookings/${bookingId}/details`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    populateBookingModal(data.booking);
                    bookingModal.show();
                } else {
                    showToast('Error', data.message || 'Failed to load booking details', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'Error loading booking details', 'error');
            })
            .finally(() => {
                hideLoading();
            });
    }

    // Populate Booking Modal
    function populateBookingModal(booking) {
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
        
        // Status with badge
        const statusBadge = document.getElementById('modalStatus');
        statusBadge.textContent = booking.status ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1) : 'N/A';
        statusBadge.className = `badge badge-${getStatusBadgeClass(booking.status)}`;
        
        // Accommodation details
        document.getElementById('modalAccommodation').textContent = booking.accommodation?.name || 'N/A';
        document.getElementById('modalLodge').textContent = booking.accommodation?.lodge_name || 'N/A';
        document.getElementById('modalAccommodationType').textContent = booking.accommodation?.type || 'N/A';
        document.getElementById('modalLocation').textContent = booking.accommodation?.location || 'N/A';
        document.getElementById('modalBeds').textContent = booking.number_of_beds || 'N/A';
        document.getElementById('modalAvailableBeds').textContent = booking.accommodation ? 
            `${booking.accommodation.available_beds}/${booking.accommodation.total_beds}` : 'N/A';
        
        // Special requests
        const specialRequests = document.getElementById('modalSpecialRequests');
        if (booking.special_requests) {
            specialRequests.textContent = booking.special_requests;
            specialRequests.className = '';
        } else {
            specialRequests.textContent = 'No special requests';
            specialRequests.className = 'text-muted';
        }
        
        document.getElementById('modalCreatedAt').textContent = formatDate(booking.created_at);
        
        // Edit and Delete links
        document.getElementById('modalFullEditLink').href = `/admin/bookings/${booking.id}/edit`;
        document.getElementById('modalDeleteLink').onclick = function() {
            confirmDelete(booking.id);
            return false;
        };

        currentBookingId = booking.id;
    }

    // Update Booking Status - COMPLETELY FIXED VERSION
    function updateBookingStatus(bookingId, newStatus) {
        if (!confirm(`Are you sure you want to update the booking status to "${newStatus}"?`)) {
            return;
        }

        console.log('Updating booking', bookingId, 'to status:', newStatus);

        // Create form data
        const formData = new FormData();
        formData.append('status', newStatus);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Use the correct endpoint from your routes
        fetch(`/admin/bookings/${bookingId}/quick-status-update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                // Try to get error message from response
                return response.text().then(text => {
                    let errorMessage = `HTTP error! status: ${response.status}`;
                    try {
                        const errorData = JSON.parse(text);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        // If not JSON, use the text as is
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
                if (window.bookingModal) {
                    window.bookingModal.hide();
                }
                // Reload the page after a short delay to see updated status
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
        });
    }

    // Load Report Details
    function loadReportDetails(reportId) {
        showLoading('Loading report details...');
        
        fetch(`/admin/reports/${reportId}/details`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    populateReportModal(data.report);
                    reportModal.show();
                } else {
                    showToast('Error', data.message || 'Failed to load report details', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'Error loading report details', 'error');
            })
            .finally(() => {
                hideLoading();
            });
    }

    // Populate Report Modal
    function populateReportModal(report) {
        document.getElementById('modalReportId').textContent = report.id;
        document.getElementById('modalReportTitle').textContent = report.title || 'N/A';
        document.getElementById('modalReportType').textContent = report.report_type_label || 'N/A';
        document.getElementById('modalReportLocation').textContent = report.location || 'N/A';
        document.getElementById('modalReportDescription').textContent = report.description || 'N/A';
        document.getElementById('modalReportCreatedAt').textContent = report.created_at || 'N/A';
        document.getElementById('modalReportResolvedAt').textContent = report.resolved_at || 'Not resolved';
        
        // User information
        document.getElementById('modalReportUser').textContent = report.user?.name || 'N/A';
        document.getElementById('modalReportUserEmail').textContent = report.user?.email || 'N/A';
        document.getElementById('modalReportAccommodation').textContent = report.accommodation?.name || 'N/A';
        
        // Status and Priority badges
        const priorityBadge = document.getElementById('modalReportPriority');
        priorityBadge.textContent = report.priority ? report.priority.charAt(0).toUpperCase() + report.priority.slice(1) : 'N/A';
        priorityBadge.className = `badge badge-${getPriorityBadgeClass(report.priority)}`;
        
        const statusBadge = document.getElementById('modalReportStatus');
        statusBadge.textContent = report.status ? report.status.replace('_', ' ').charAt(0).toUpperCase() + report.status.replace('_', ' ').slice(1) : 'N/A';
        statusBadge.className = `badge badge-${getReportStatusBadgeClass(report.status)}`;
        
        // Admin notes
        const adminNotes = document.getElementById('modalReportAdminNotes');
        const noNotes = document.getElementById('modalReportNoNotes');
        if (report.admin_notes) {
            adminNotes.innerHTML = report.admin_notes;
            noNotes.style.display = 'none';
        } else {
            adminNotes.innerHTML = '';
            noNotes.style.display = 'block';
        }

        currentReportId = report.id;
    }

    // Update Report Status
    function updateReportStatus(reportId, newStatus) {
        if (!confirm(`Are you sure you want to update the report status to "${newStatus}"?`)) {
            return;
        }

        const formData = new FormData();
        formData.append('status', newStatus);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(`/admin/reports/${reportId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Success', `Report status updated to ${newStatus}`, 'success');
                if (window.reportModal) {
                    window.reportModal.hide();
                }
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
        });
    }

    // Delete Confirmation
    window.confirmDelete = function(bookingId) {
        currentBookingId = bookingId;
        deleteModal.show();
    };

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/bookings/${currentBookingId}`;
        form.submit();
    });

    // ===== UTILITY FUNCTIONS =====

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

    function getReportStatusBadgeClass(status) {
        const statusClasses = {
            'pending': 'warning',
            'in_progress': 'info',
            'resolved': 'success',
            'cancelled': 'secondary'
        };
        return statusClasses[status] || 'secondary';
    }

    function getPriorityBadgeClass(priority) {
        const priorityClasses = {
            'low': 'success',
            'medium': 'warning',
            'high': 'danger',
            'urgent': 'danger'
        };
        return priorityClasses[priority] || 'secondary';
    }

    function showLoading(message = 'Loading...') {
        // You can implement a loading spinner here if needed
        console.log(message);
    }

    function hideLoading() {
        // Hide loading spinner
    }

    // Toast Notification
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
        
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    // Add CSS for badges and styling
    const style = document.createElement('style');
    style.textContent = `
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-info { background-color: #17a2b8; }
        .badge-secondary { background-color: #6c757d; }
        .badge-danger { background-color: #dc3545; }
        .table-light { background-color: #f8f9fa; }
        .col-xl-15 { flex: 0 0 20%; max-width: 20%; }
        @media (max-width: 1200px) { .col-xl-15 { flex: 0 0 33.333333%; max-width: 33.333333%; } }
        @media (max-width: 768px) { .col-xl-15 { flex: 0 0 50%; max-width: 50%; } }
        @media (max-width: 576px) { .col-xl-15 { flex: 0 0 100%; max-width: 100%; } }
        .widget-visitor-card { transition: transform 0.2s; }
        .widget-visitor-card:hover { transform: translateY(-2px); }
    `;
    document.head.appendChild(style);
});
</script>
@endsection