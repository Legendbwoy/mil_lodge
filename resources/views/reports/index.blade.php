@extends('layouts.admin')

@section('title', 'Reports Management - Akafia')
@section('page-title', 'Reports Management')

@section('content')
<div class="container-fluid">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Reports Management</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Reports</a></li>
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
                                <h4 class="mb-0">{{ $totalReports ?? $reports->total() }}</h4>
                                <span>Total Reports</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-alert-triangle display-4 opacity-50"></i>
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
                                <h4 class="mb-0">{{ $pendingReportsCount ?? $reports->where('status', 'pending')->count() }}</h4>
                                <span>Pending</span>
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
                                <h4 class="mb-0">{{ $inProgressCount ?? $reports->where('status', 'in_progress')->count() }}</h4>
                                <span>In Progress</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-loader display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $urgentCount ?? $reports->where('priority', 'urgent')->count() }}</h4>
                                <span>Urgent Priority</span>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="feather icon-alert-octagon display-4 opacity-50"></i>
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
                        <h5 class="mb-0"><i class="feather icon-alert-triangle me-2"></i>Reports Management</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="feather icon-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">All Reports</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">By Status</h6></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}">Pending</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}">In Progress</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'resolved']) }}">Resolved</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}">Cancelled</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">By Priority</h6></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['priority' => 'urgent']) }}">Urgent</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['priority' => 'high']) }}">High</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['priority' => 'medium']) }}">Medium</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['priority' => 'low']) }}">Low</a></li>
                            </ul>
                            <a href="{{ route('reports.create') }}" class="btn btn-primary btn-sm ms-2" target="_blank">
                                <i class="feather icon-plus"></i> New Report
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Title & Description</th>
                                        <th>Location</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $report)
                                        <tr class="{{ $report->priority === 'urgent' && $report->status !== 'resolved' ? 'table-danger' : '' }} {{ $report->status === 'pending' ? 'table-warning' : '' }}">
                                            <td>
                                                <strong>#{{ $report->id }}</strong>
                                                @if($report->created_at->isToday())
                                                    <span class="badge bg-success ms-1">New</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <i class="feather icon-{{ $report->report_type === 'amenity_issue' ? 'tool' : ($report->report_type === 'repair' ? 'settings' : 'home') }} me-1"></i>
                                                    {{ $report->report_type_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong class="d-block">{{ Str::limit($report->title, 40) }}</strong>
                                                    <small class="text-muted">{{ Str::limit($report->description, 50) }}</small>
                                                </div>
                                                @if($report->user)
                                                    <small class="text-primary">
                                                        <i class="feather icon-user me-1"></i>{{ $report->user->name }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="d-block">{{ Str::limit($report->location, 25) }}</span>
                                                @if($report->accommodation)
                                                    <small class="text-muted">{{ $report->accommodation->name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $report->priority_badge }}">
                                                    <i class="feather icon-{{ $report->priority === 'urgent' ? 'alert-octagon' : ($report->priority === 'high' ? 'alert-circle' : 'info') }} me-1"></i>
                                                    {{ ucfirst($report->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $report->status_badge }}">
                                                    <i class="feather icon-{{ $report->status === 'pending' ? 'clock' : ($report->status === 'in_progress' ? 'loader' : ($report->status === 'resolved' ? 'check-circle' : 'x-circle')) }} me-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                                @if($report->resolved_at)
                                                    <br>
                                                    <small class="text-muted">Resolved: {{ $report->resolved_at->format('M d') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="d-block">{{ $report->created_at->format('M d, Y') }}</span>
                                                <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="d-block">{{ $report->updated_at->format('M d, Y') }}</span>
                                                <small class="text-muted">{{ $report->updated_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    {{-- View Button --}}
                                                    <button class="btn btn-outline-info btn-view-report" data-report-id="{{ $report->id }}" title="View Details">
                                                        <i class="feather icon-eye"></i>
                                                    </button>
                                                    
                                                    {{-- Edit Button with Dropdown --}}
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Manage">
                                                            <i class="feather icon-edit"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('reports.show', $report) }}" target="_blank">
                                                                    <i class="feather icon-external-link me-1"></i>View Full Report
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
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
                                                                    <i class="feather icon-message-square me-1"></i>Add Admin Notes
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
                                                    <i class="feather icon-alert-triangle display-1 text-muted"></i>
                                                    <h4 class="mt-3">No reports found</h4>
                                                    <p class="text-muted">No reports match your current filters.</p>
                                                    <a href="{{ route('reports.create') }}" class="btn btn-primary" target="_blank">
                                                        <i class="feather icon-plus me-1"></i>Create New Report
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
                                Showing {{ $reports->firstItem() ?? 0 }} to {{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} entries
                            </div>
                            <div>
                                {{ $reports->links() }}
                            </div>
                        </div>
                    </div>
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
    const reportModal = new bootstrap.Modal(document.getElementById('reportDetailsModal'));
    const reportNotesModal = new bootstrap.Modal(document.getElementById('reportNotesModal'));

    // Add click event listeners to all view buttons
    document.querySelectorAll('.btn-view-report').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const reportId = this.getAttribute('data-report-id');
            loadReportDetails(reportId);
        });
    });

    function loadReportDetails(reportId) {
        // Show loading state
        document.getElementById('modalReportId').textContent = reportId;
        document.getElementById('modalReportTitle').textContent = 'Loading...';
        
        // Fetch report details via AJAX
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
        priorityBadge.className = `badge ${getReportPriorityBadgeClass(report.priority)}`;
        
        // Status badge
        const statusBadge = document.getElementById('modalReportStatus');
        statusBadge.textContent = report.status.charAt(0).toUpperCase() + report.status.slice(1).replace('_', ' ');
        statusBadge.className = `badge ${getReportStatusBadgeClass(report.status)}`;
        
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

    function getReportPriorityBadgeClass(priority) {
        const priorityClasses = {
            'low': 'bg-info',
            'medium': 'bg-warning',
            'high': 'bg-danger',
            'urgent': 'bg-dark'
        };
        return priorityClasses[priority] || 'bg-secondary';
    }

    function getReportStatusBadgeClass(status) {
        const statusClasses = {
            'pending': 'bg-warning',
            'in_progress': 'bg-info',
            'resolved': 'bg-success',
            'cancelled': 'bg-secondary'
        };
        return statusClasses[status] || 'bg-secondary';
    }

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
                const statusBadge = document.querySelector(`tr:has(button[data-report-id="${reportId}"]) .badge`);
                if (statusBadge) {
                    statusBadge.textContent = data.report.status.charAt(0).toUpperCase() + data.report.status.slice(1).replace('_', ' ');
                    statusBadge.className = `badge ${getReportStatusBadgeClass(data.report.status)}`;
                }
                
                // Update modal if open
                if (document.getElementById('modalReportId').textContent === reportId) {
                    const modalStatusBadge = document.getElementById('modalReportStatus');
                    modalStatusBadge.textContent = data.report.status.charAt(0).toUpperCase() + data.report.status.slice(1).replace('_', ' ');
                    modalStatusBadge.className = `badge ${getReportStatusBadgeClass(data.report.status)}`;
                    
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
            showToast('Error', 'An error occurred while updating status', 'error');
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
        .empty-state {
            text-align: center;
            padding: 2rem 0;
        }
        .table > :not(caption) > * > * {
            vertical-align: middle;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection