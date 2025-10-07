@extends('layouts.admin')

@section('page-title', 'Dashboard Overview')

@section('content')
<div class="container-fluid">

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
                    <h5 class="text-white">GH¢{{ number_format($totalRevenue, 2) }}</h5>
                    <h6 class="text-white">Total Revenue</h6>
                    <i class="feather icon-briefcase"></i>
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

    {{-- ===== Accommodation Management Overview ===== --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="feather icon-grid me-1"></i> Accommodation Management</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#accommodationStatusModal">
                        <i class="feather icon-edit me-1"></i>Manage Status
                    </button>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="bg-light rounded p-3">
                                <h3 class="text-primary mb-1">{{ $totalAccommodations }}</h3>
                                <small class="text-muted">Total Rooms</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light rounded p-3">
                                <h3 class="text-success mb-1">{{ $availableAccommodations }}</h3>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="bg-light rounded p-3">
                                <h3 class="text-warning mb-1">{{ $occupiedAccommodations }}</h3>
                                <small class="text-muted">Occupied</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $accommodationAvailability }}%">
                            Available ({{ $accommodationAvailability }}%)
                        </div>
                        <div class="progress-bar bg-warning" style="width: {{ 100 - $accommodationAvailability }}%">
                            Occupied ({{ 100 - $accommodationAvailability }}%)
                        </div>
                    </div>

                    {{-- Quick Status Actions --}}
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Quick Actions</h6>
                            <div class="btn-group w-100">
                                <button type="button" class="btn btn-outline-success btn-sm quick-room-action" data-action="make_available">
                                    <i class="feather icon-check-circle me-1"></i>Mark Available
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm quick-room-action" data-action="make_occupied">
                                    <i class="feather icon-user me-1"></i>Mark Occupied
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm quick-room-action" data-action="make_maintenance">
                                    <i class="feather icon-tool me-1"></i>Maintenance
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-home me-1"></i> Lodge Breakdown</h5>
                </div>
                <div class="card-body">
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
                                Occupied: {{ $akafiaStats->occupied ?? 0 }} | 
                                Maintenance: {{ $akafiaStats->maintenance ?? 0 }}
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
                                Occupied: {{ $oppongStats->occupied ?? 0 }} | 
                                Maintenance: {{ $oppongStats->maintenance ?? 0 }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .col-xl-15 {
        flex: 0 0 20%;
        max-width: 20%;
    }

    @media (max-width: 1200px) {
        .col-xl-15 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }

    @media (max-width: 768px) {
        .col-xl-15 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 576px) {
        .col-xl-15 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    </style>

    {{-- ===== Quick Actions ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="feather icon-bolt me-1"></i> Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 text-center">
                <div class="col-md-3">
                    <a href="{{ route('admin.accommodations.create') }}" class="btn btn-primary w-100">
                        <i class="feather icon-plus me-1"></i>Add Room
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-success w-100">
                        <i class="feather icon-plus me-1"></i>Create Booking
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-info w-100">
                        <i class="feather icon-user-plus me-1"></i>Add User
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('reports.index') }}" class="btn btn-warning w-100">
                        <i class="feather icon-file-text me-1"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Charts & Monthly Indicators ===== --}}
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-md-8 mb-4 mb-md-0">
            <div class="card">
                <div class="card-header">
                    <h5>Revenue Overview (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <div id="revenue-chart" style="height:300px;"></div>
                </div>
            </div>
        </div>

        <!-- Monthly Stats -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h6>This Month Revenue</h6>
                    <h3 class="mt-3 text-primary">GH¢{{ number_format($monthlyRevenue, 2) }}</h3>
                    <p class="text-muted mt-2">Revenue generated this month</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <h6>This Month Bookings</h6>
                    <h3 class="mt-3 text-success">{{ $bookingsThisMonth }}</h3>
                    <p class="text-muted mt-2">Bookings made this month</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== System Overview ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="feather icon-activity me-1"></i> System Overview</h5>
        </div>
        <div class="card-body row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h6 class="mb-3">Booking Status</h6>
                @foreach (['confirmed'=>'success','pending'=>'warning','checked_in'=>'info','cancelled'=>'danger'] as $status=>$color)
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2 text-capitalize" style="width:90px;">{{ $status }}</span>
                        <div class="progress flex-grow-1">
                            <div class="progress-bar bg-{{ $color }} progress-bar-striped progress-bar-animated"
                                style="width: {{ $bookingStatusPercentages[$status] ?? 0 }}%">
                            </div>
                        </div>
                        <span class="ms-2">{{ $bookingStatusPercentages[$status] ?? 0 }}%</span>
                    </div>
                @endforeach
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <h6 class="mb-3">Accommodation Status</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span>Available Rooms</span>
                    <span class="fw-bold">{{ $availableAccommodations }} ({{ $accommodationAvailability }}%)</span>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                        style="width: {{ $accommodationAvailability }}%">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Occupied Rooms</span>
                    <span class="fw-bold">{{ $occupiedAccommodations }} ({{ 100 - $accommodationAvailability }}%)</span>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span>Under Maintenance</span>
                    <span class="fw-bold">{{ $maintenanceAccommodations ?? 0 }} ({{ $maintenancePercentage ?? 0 }}%)</span>
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="mb-3">Bed Occupancy</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span>Available Beds</span>
                    <span class="fw-bold">{{ $availableBeds }} ({{ $totalBeds > 0 ? round(($availableBeds / $totalBeds) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                        style="width: {{ $totalBeds > 0 ? ($availableBeds / $totalBeds) * 100 : 0 }}%">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Occupied Beds</span>
                    <span class="fw-bold">{{ $occupiedBeds }} ({{ $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0 }}%)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Reports Analytics ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="feather icon-alert-triangle me-1"></i> Reports Analytics</h5>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">View All Reports</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <!-- Total Reports Card -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card bg-c-blue text-white">
                        <div class="card-body text-center">
                            <h2 class="text-white">{{ $totalReports }}</h2>
                            <h6 class="text-white">Total Reports</h6>
                            <i class="feather icon-alert-triangle"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Reports This Month -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card bg-c-green text-white">
                        <div class="card-body text-center">
                            <h2 class="text-white">{{ $reportsThisMonth }}</h2>
                            <h6 class="text-white">This Month</h6>
                            <i class="feather icon-calendar"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Reports -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card bg-c-yellow text-white">
                        <div class="card-body text-center">
                            <h2 class="text-white">{{ $reportStatuses['pending'] }}</h2>
                            <h6 class="text-white">Pending</h6>
                            <i class="feather icon-clock"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Urgent Reports -->
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card bg-c-red text-white">
                        <div class="card-body text-center">
                            <h2 class="text-white">{{ $reportPriorities['urgent'] }}</h2>
                            <h6 class="text-white">Urgent</h6>
                            <i class="feather icon-alert-octagon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Report Status Distribution -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <h6 class="mb-3">Report Status</h6>
                    @foreach (['pending'=>'warning','in_progress'=>'info','resolved'=>'success','cancelled'=>'secondary'] as $status=>$color)
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2 text-capitalize" style="width:100px;">{{ str_replace('_', ' ', $status) }}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar bg-{{ $color }} progress-bar-striped progress-bar-animated"
                                    style="width: {{ $totalReports > 0 ? round(($reportStatuses[$status] / $totalReports) * 100, 1) : 0 }}%">
                                </div>
                            </div>
                            <span class="ms-2">{{ $reportStatuses[$status] }}</span>
                        </div>
                    @endforeach
                </div>
                
                <!-- Report Types -->
                <div class="col-md-6">
                    <h6 class="mb-3">Report Types</h6>
                    @foreach (['amenity_issue'=>'Amenity Issue','repair'=>'Repair','renovation'=>'Renovation'] as $type=>$label)
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2" style="width:120px;">{{ $label }}</span>
                            <div class="progress flex-grow-1">
                                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                                    style="width: {{ $totalReports > 0 ? round(($reportTypes[$type] / $totalReports) * 100, 1) : 0 }}%">
                                </div>
                            </div>
                            <span class="ms-2">{{ $reportTypes[$type] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Recent Reports ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="feather icon-list me-1"></i> Recent Reports</h5>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentReports as $report)
                    <tr>
                        <td><strong>#{{ $report->id }}</strong></td>
                        <td>
                            <div class="fw-bold">{{ Str::limit($report->title, 30) }}</div>
                            <small class="text-muted">{{ $report->location }}</small>
                        </td>
                        <td>{{ $report->report_type_label }}</td>
                        <td>
                            <span class="badge badge-{{ $report->priority_badge }}">
                                {{ ucfirst($report->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $report->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td>{{ $report->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                {{-- View Button --}}
                                <button class="btn btn-outline-info btn-view-report" data-report-id="{{ $report->id }}">
                                    <i class="feather icon-eye"></i>
                                </button>
                                
                                {{-- Edit Button with Dropdown --}}
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="feather icon-edit"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><h6 class="dropdown-header">Update Status</h6></li>
                                        <li>
                                            <a class="dropdown-item report-status-update" href="#" data-report-id="{{ $report->id }}" data-status="pending">
                                                <span class="badge badge-warning me-1">●</span>Mark as Pending
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item report-status-update" href="#" data-report-id="{{ $report->id }}" data-status="in_progress">
                                                <span class="badge badge-info me-1">●</span>Mark as In Progress
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item report-status-update" href="#" data-report-id="{{ $report->id }}" data-status="resolved">
                                                <span class="badge badge-success me-1">●</span>Mark as Resolved
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item report-status-update" href="#" data-report-id="{{ $report->id }}" data-status="cancelled">
                                                <span class="badge badge-secondary me-1">●</span>Mark as Cancelled
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportNotesModal" data-report-id="{{ $report->id }}">
                                                <i class="feather icon-message-square me-1"></i>Add Notes
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No recent reports found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== Recent Bookings ===== --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="feather icon-clock me-1"></i> Recent Bookings</h5>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Property</th>
                        <th>Beds</th>
                        <th>Dates</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentBookings as $booking)
                    <tr>
                        <td><strong>#{{ $booking->id }}</strong></td>
                        <td>
                            <div class="fw-bold">{{ $booking->guest_name }}</div>
                            <small class="text-muted">{{ $booking->guest_email }}</small>
                        </td>
                        <td>{{ $booking->accommodation->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $booking->number_of_beds }} Bed{{ $booking->number_of_beds > 1 ? 's' : '' }}</span>
                        </td>
                        <td>
                            <small>{{ $booking->check_in_date->format('M d, Y') }}</small><br>
                            <small class="text-muted">to {{ $booking->check_out_date->format('M d, Y') }}</small>
                        </td>
                        <td class="fw-bold">GH¢{{ number_format($booking->total_amount, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $booking->status_badge }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                {{-- View Button --}}
                                <button class="btn btn-outline-info btn-view-booking" data-booking-id="{{ $booking->id }}">
                                    <i class="feather icon-eye"></i>
                                </button>
                                
                                {{-- Edit Button with Dropdown --}}
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="feather icon-edit"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.bookings.edit', $booking->id) }}">
                                            <i class="feather icon-edit me-1"></i>Edit Details
                                        </a></li>
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
                                            <a class="dropdown-item quick-status-update" href="#" data-booking-id="{{ $booking->id }}" data-status="cancelled">
                                                <span class="badge badge-danger me-1">●</span>Mark as Cancelled
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">No recent bookings found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===== Accommodation Status Management Modal ===== --}}
<div class="modal fade" id="accommodationStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Accommodation Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="accommodationStatusTable">
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Lodge</th>
                                <th>Current Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accommodations as $accommodation)
                            <tr data-accommodation-id="{{ $accommodation->id }}">
                                <td>
                                    <strong>{{ $accommodation->name }}</strong>
                                    <br><small class="text-muted">{{ $accommodation->room_number }}</small>
                                </td>
                                <td>{{ $accommodation->lodge }}</td>
                                <td>
                                    <span class="badge badge-{{ $accommodation->status_badge }}">
                                        {{ ucfirst($accommodation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-success accommodation-status-update" 
                                                data-accommodation-id="{{ $accommodation->id }}" 
                                                data-status="available">
                                            Available
                                        </button>
                                        <button type="button" class="btn btn-outline-warning accommodation-status-update" 
                                                data-accommodation-id="{{ $accommodation->id }}" 
                                                data-status="occupied">
                                            Occupied
                                        </button>
                                        <button type="button" class="btn btn-outline-danger accommodation-status-update" 
                                                data-accommodation-id="{{ $accommodation->id }}" 
                                                data-status="maintenance">
                                            Maintenance
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Booking Information</h6>
                        <p><strong>Status:</strong> <span id="modalStatus" class="badge"></span></p>
                        <p><strong>Beds Booked:</strong> <span id="modalBedsBooked"></span></p>
                        <p><strong>Check-in:</strong> <span id="modalCheckIn"></span></p>
                        <p><strong>Check-out:</strong> <span id="modalCheckOut"></span></p>
                        <p><strong>Nights:</strong> <span id="modalNights"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Accommodation Details</h6>
                        <p><strong>Property:</strong> <span id="modalAccommodation"></span></p>
                        <p><strong>Type:</strong> <span id="modalAccommodationType"></span></p>
                        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                        <p><strong>Room:</strong> <span id="modalRoomNumber"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Payment Details</h6>
                        <p><strong>Total Amount:</strong> GH¢<span id="modalTotalAmount"></span></p>
                        <p><strong>Payment Status:</strong> <span id="modalPaymentStatus" class="badge"></span></p>
                        <p><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                        <p><strong>Booked On:</strong> <span id="modalCreatedAt"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Purpose of Booking</h6>
                        <p id="modalSpecialRequests" class="text-muted"></p>
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
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="cancelled">
                                <span class="badge badge-danger me-1">●</span>Mark as Cancelled
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="modalFullEditLink">
                            <i class="feather icon-edit me-1"></i>Full Edit
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Report Details Modal ===== --}}
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
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Submission Details</h6>
                        <p><strong>Submitted:</strong> <span id="modalReportCreated"></span></p>
                        <p><strong>Resolved:</strong> <span id="modalReportResolved">Not resolved</span></p>
                        <p><strong>User:</strong> <span id="modalReportUser">Guest</span></p>
                        <p><strong>Accommodation:</strong> <span id="modalReportAccommodation">N/A</span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Description</h6>
                        <p id="modalReportDescription" class="text-muted"></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Admin Notes</h6>
                        <p id="modalReportAdminNotes" class="text-muted">No admin notes yet.</p>
                    </div>
                </div>

                <div class="row mt-3" id="modalReportImagesSection" style="display: none;">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Attached Images</h6>
                        <div id="modalReportImages" class="row"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportNotesModal" id="modalAddNotesBtn">
                    <i class="feather icon-message-square me-1"></i>Add Notes
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== Report Notes Modal ===== --}}
<div class="modal fade" id="reportNotesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Admin Notes - Report #<span id="notesReportId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reportNotesForm">
                <div class="modal-body">
                    <input type="hidden" id="notesReportIdInput" name="report_id">
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Admin Notes</label>
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="4" placeholder="Add notes about this report..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="statusUpdate" class="form-label">Update Status</label>
                        <select class="form-select" id="statusUpdate" name="status">
                            <option value="">Keep current status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="feather icon-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== ApexCharts & Bootstrap JS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl)
    });

    // Revenue Chart
    var options = {
        chart: { type: 'area', height: 300, toolbar: { show: false } },
        series: [{
            name: 'Revenue',
            data: @json($revenueData['values'])
        }],
        xaxis: { categories: @json($revenueData['months']) },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        colors: ['#4e73df']
    };
    new ApexCharts(document.querySelector("#revenue-chart"), options).render();

    // Modal functionality
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
    const accommodationModal = new bootstrap.Modal(document.getElementById('accommodationStatusModal'));
    
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateModal(data.booking);
                    bookingModal.show();
                } else {
                    alert('Error loading booking details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading booking details');
            });
    }

    function populateModal(booking) {
        // Basic booking info
        document.getElementById('modalBookingId').textContent = booking.id;
        document.getElementById('modalGuestName').textContent = booking.guest_name;
        document.getElementById('modalGuestEmail').textContent = booking.guest_email;
        document.getElementById('modalGuestPhone').textContent = booking.guest_phone || 'N/A';
        
        // Dates and beds
        document.getElementById('modalCheckIn').textContent = formatDate(booking.check_in_date);
        document.getElementById('modalCheckOut').textContent = formatDate(booking.check_out_date);
        document.getElementById('modalNights').textContent = calculateNights(booking.check_in_date, booking.check_out_date);
        document.getElementById('modalBedsBooked').textContent = booking.number_of_beds;
        
        // Status with badge
        const statusBadge = document.getElementById('modalStatus');
        statusBadge.textContent = booking.status.charAt(0).toUpperCase() + booking.status.slice(1);
        statusBadge.className = `badge badge-${getStatusBadgeClass(booking.status)}`;
        
        // Accommodation details
        document.getElementById('modalAccommodation').textContent = booking.accommodation?.name || 'N/A';
        document.getElementById('modalAccommodationType').textContent = booking.accommodation?.type || 'N/A';
        document.getElementById('modalLocation').textContent = booking.accommodation?.location || 'N/A';
        document.getElementById('modalRoomNumber').textContent = booking.room_number || 'N/A';
        
        // Payment info
        document.getElementById('modalTotalAmount').textContent = parseFloat(booking.total_amount).toFixed(2);
        document.getElementById('modalPaymentStatus').textContent = booking.payment_status || 'N/A';
        document.getElementById('modalPaymentStatus').className = `badge badge-${getPaymentStatusBadgeClass(booking.payment_status)}`;
        document.getElementById('modalPaymentMethod').textContent = booking.payment_method || 'N/A';
        document.getElementById('modalCreatedAt').textContent = formatDate(booking.created_at);
        
        // Purpose of Booking
        const specialRequests = document.getElementById('modalSpecialRequests');
        if (specialRequests) {
            specialRequests.textContent = booking.special_requests || 'No Purpose of Booking';
        }
        
        // Edit link
        document.getElementById('modalFullEditLink').href = `/admin/bookings/${booking.id}/edit`;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }

    function calculateNights(checkIn, checkOut) {
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const diffTime = Math.abs(checkOutDate - checkInDate);
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }

    function getStatusBadgeClass(status) {
        const statusClasses = {
            'confirmed': 'success',
            'pending': 'warning',
            'checked_in': 'info',
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
            const modal = document.getElementById('bookingDetailsModal');
            const bookingId = modal.querySelector('#modalBookingId').textContent;
            const newStatus = e.target.getAttribute('data-status');
            
            updateBookingStatus(bookingId, newStatus);
        }
    });

    function updateBookingStatus(bookingId, newStatus) {
        if (!confirm('Are you sure you want to update the booking status?')) {
            return;
        }

        // Show loading state
        const button = document.querySelector(`.quick-status-update[data-booking-id="${bookingId}"][data-status="${newStatus}"]`) || 
                       document.querySelector('.modal-quick-status-update[data-status="' + newStatus + '"]');
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="feather icon-loader spin me-1"></i>Updating...';
        }

        // Send AJAX request
        fetch(`/admin/bookings/${bookingId}/quick-status-update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the status badge in the table
                const statusBadge = document.querySelector(`tr:has(button[data-booking-id="${bookingId}"]) .badge`);
                if (statusBadge) {
                    statusBadge.textContent = data.booking.status.charAt(0).toUpperCase() + data.booking.status.slice(1);
                    statusBadge.className = `badge badge-${getStatusBadgeClass(data.booking.status)}`;
                }
                
                // Update modal status if open
                updateModalStatus(data.booking.status);
                
                // Show success message
                showToast('Success', 'Booking status updated successfully', 'success');
                
                // Close dropdowns
                const dropdowns = document.querySelectorAll('.dropdown-menu.show');
                dropdowns.forEach(dropdown => {
                    const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                    if (dropdownInstance) {
                        dropdownInstance.hide();
                    }
                });
            } else {
                showToast('Error', data.message || 'Failed to update status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating status', 'error');
        })
        .finally(() => {
            // Restore button text
            if (button) {
                button.innerHTML = originalText;
            }
        });
    }

    // Update modal status display when status changes
    function updateModalStatus(status) {
        const statusBadge = document.getElementById('modalStatus');
        if (statusBadge) {
            statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            statusBadge.className = `badge badge-${getStatusBadgeClass(status)}`;
        }
    }

    // Accommodation Status Management
    document.querySelectorAll('.accommodation-status-update').forEach(button => {
        button.addEventListener('click', function() {
            const accommodationId = this.getAttribute('data-accommodation-id');
            const newStatus = this.getAttribute('data-status');
            
            updateAccommodationStatus(accommodationId, newStatus);
        });
    });

    // Quick room actions
    document.querySelectorAll('.quick-room-action').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            
            // For now, just open the accommodation status modal
            // In a real implementation, you might want to show a selection modal
            accommodationModal.show();
        });
    });

    function updateAccommodationStatus(accommodationId, newStatus) {
        if (!confirm('Are you sure you want to update this room status?')) {
            return;
        }

        const button = document.querySelector(`.accommodation-status-update[data-accommodation-id="${accommodationId}"][data-status="${newStatus}"]`);
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="feather icon-loader spin me-1"></i>';
        }

        fetch(`/admin/accommodations/${accommodationId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the status badge in the table
                const row = document.querySelector(`tr[data-accommodation-id="${accommodationId}"]`);
                const statusBadge = row.querySelector('.badge');
                if (statusBadge) {
                    statusBadge.textContent = data.accommodation.status.charAt(0).toUpperCase() + data.accommodation.status.slice(1);
                    statusBadge.className = `badge badge-${data.accommodation.status_badge}`;
                }
                
                showToast('Success', 'Room status updated successfully', 'success');
                
                // Reload page to update dashboard statistics
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast('Error', data.message || 'Failed to update room status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating room status', 'error');
        })
        .finally(() => {
            if (button) {
                button.innerHTML = originalText;
            }
        });
    }

    // Toast notification function
    function showToast(title, message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const bgColor = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        
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
            delay: 3000
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
    `;
    document.head.appendChild(style);

    // Report functionality
    const reportModal = new bootstrap.Modal(document.getElementById('reportDetailsModal'));
    const reportNotesModal = new bootstrap.Modal(document.getElementById('reportNotesModal'));

    // View report details
    document.querySelectorAll('.btn-view-report').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const reportId = this.getAttribute('data-report-id');
            loadReportDetails(reportId);
        });
    });

    // Quick status update for reports
    document.querySelectorAll('.report-status-update').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const reportId = this.getAttribute('data-report-id');
            const newStatus = this.getAttribute('data-status');
            updateReportStatus(reportId, newStatus);
        });
    });

    // Report notes modal
    document.addEventListener('click', function(e) {
        if (e.target.hasAttribute('data-bs-target') && e.target.getAttribute('data-bs-target') === '#reportNotesModal') {
            const reportId = e.target.getAttribute('data-report-id') || document.getElementById('modalReportId').textContent;
            document.getElementById('notesReportId').textContent = reportId;
            document.getElementById('notesReportIdInput').value = reportId;
        }
    });

    // Report notes form submission
    document.getElementById('reportNotesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const reportId = document.getElementById('notesReportIdInput').value;
        const adminNotes = document.getElementById('adminNotes').value;
        const status = document.getElementById('statusUpdate').value;
        
        updateReportStatus(reportId, status, adminNotes);
    });

    function loadReportDetails(reportId) {
        document.getElementById('modalReportId').textContent = reportId;
        document.getElementById('modalReportTitle').textContent = 'Loading...';
        
        fetch(`/admin/reports/${reportId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateReportModal(data.report);
                    reportModal.show();
                } else {
                    alert('Error loading report details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading report details');
            });
    }

    function populateReportModal(report) {
        document.getElementById('modalReportId').textContent = report.id;
        document.getElementById('modalReportTitle').textContent = report.title;
        document.getElementById('modalReportType').textContent = report.report_type_label;
        document.getElementById('modalReportLocation').textContent = report.location;
        document.getElementById('modalReportDescription').textContent = report.description;
        document.getElementById('modalReportCreated').textContent = report.created_at;
        
        // Priority badge
        const priorityBadge = document.getElementById('modalReportPriority');
        priorityBadge.textContent = report.priority.charAt(0).toUpperCase() + report.priority.slice(1);
        priorityBadge.className = `badge badge-${getReportPriorityBadgeClass(report.priority)}`;
        
        // Status badge
        const statusBadge = document.getElementById('modalReportStatus');
        statusBadge.textContent = report.status.charAt(0).toUpperCase() + report.status.slice(1).replace('_', ' ');
        statusBadge.className = `badge badge-${getReportStatusBadgeClass(report.status)}`;
        
        // Resolved date
        document.getElementById('modalReportResolved').textContent = report.resolved_at || 'Not resolved';
        
        // User info
        document.getElementById('modalReportUser').textContent = report.user ? `${report.user.name} (${report.user.email})` : 'Guest';
        
        // Accommodation info
        document.getElementById('modalReportAccommodation').textContent = report.accommodation ? `${report.accommodation.name} - ${report.accommodation.location}` : 'N/A';
        
        // Admin notes
        document.getElementById('modalReportAdminNotes').textContent = report.admin_notes || 'No admin notes yet.';
        
        // Images
        const imagesSection = document.getElementById('modalReportImagesSection');
        const imagesContainer = document.getElementById('modalReportImages');
        imagesContainer.innerHTML = '';
        
        if (report.images && report.images.length > 0) {
            imagesSection.style.display = 'block';
            report.images.forEach(image => {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-2';
                col.innerHTML = `
                    <a href="/storage/${image}" target="_blank">
                        <img src="/storage/${image}" class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                    </a>
                `;
                imagesContainer.appendChild(col);
            });
        } else {
            imagesSection.style.display = 'none';
        }
        
        // Set notes button
        document.getElementById('modalAddNotesBtn').setAttribute('data-report-id', report.id);
    }

    function updateReportStatus(reportId, newStatus, adminNotes = '') {
        if (newStatus && !confirm('Are you sure you want to update the report status?')) {
            return;
        }

        const button = document.querySelector(`.report-status-update[data-report-id="${reportId}"][data-status="${newStatus}"]`);
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="feather icon-loader spin me-1"></i>Updating...';
        }

        fetch(`/admin/reports/${reportId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: newStatus,
                admin_notes: adminNotes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the status badge in the table
                const statusBadge = document.querySelector(`tr:has(button[data-report-id="${reportId}"]) .badge.badge-${getReportStatusBadgeClass('pending')}`);
                if (statusBadge) {
                    statusBadge.textContent = data.report.status.charAt(0).toUpperCase() + data.report.status.slice(1).replace('_', ' ');
                    statusBadge.className = `badge badge-${data.report.status_badge}`;
                }
                
                // Update modal if open
                if (document.getElementById('modalReportId').textContent === reportId) {
                    const modalStatusBadge = document.getElementById('modalReportStatus');
                    modalStatusBadge.textContent = data.report.status.charAt(0).toUpperCase() + data.report.status.slice(1).replace('_', ' ');
                    modalStatusBadge.className = `badge badge-${data.report.status_badge}`;
                    
                    if (data.report.admin_notes) {
                        document.getElementById('modalReportAdminNotes').textContent = data.report.admin_notes;
                    }
                    if (data.report.resolved_at) {
                        document.getElementById('modalReportResolved').textContent = data.report.resolved_at;
                    }
                }
                
                showToast('Success', 'Report status updated successfully', 'success');
                
                // Close modals and dropdowns
                reportNotesModal.hide();
                const dropdowns = document.querySelectorAll('.dropdown-menu.show');
                dropdowns.forEach(dropdown => {
                    const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                    if (dropdownInstance) {
                        dropdownInstance.hide();
                    }
                });
                
                // Reload page after a short delay to reflect changes in analytics
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showToast('Error', data.message || 'Failed to update status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'An error occurred while updating status', 'error');
        })
        .finally(() => {
            if (button) {
                button.innerHTML = originalText;
            }
        });
    }

    function getReportPriorityBadgeClass(priority) {
        const priorityClasses = {
            'low': 'info',
            'medium': 'warning',
            'high': 'danger',
            'urgent': 'dark'
        };
        return priorityClasses[priority] || 'secondary';
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
});
</script>
@endsection