@extends('layouts.admin')

@section('page-title', 'Analytics & Reports')

@section('content')
<div class="container-fluid">

    {{-- ===== Analytics Header ===== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="feather icon-bar-chart-2 me-2"></i>Analytics & Reports</h4>
                            <p class="text-muted mb-0">Comprehensive analytics and performance metrics</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="feather icon-download me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item export-analytics" href="#" data-type="bookings">Booking Data</a></li>
                                    <li><a class="dropdown-item export-analytics" href="#" data-type="occupancy">Occupancy Data</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="window.print()"><i class="feather icon-printer me-1"></i> Print Report</a></li>
                                </ul>
                            </div>
                            
                            <div class="btn-group ms-2">
                                <select class="form-select" id="yearFilter">
                                    @foreach($availableYears as $availableYear)
                                        <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Booking Analytics ===== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-calendar me-1"></i> Booking Analytics - {{ $year }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="booking-trend-chart" style="height: 300px;"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-primary">{{ $bookingAnalytics['total_bookings'] }}</h3>
                                            <p class="mb-0">Total Bookings</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-success">{{ $bookingAnalytics['avg_monthly_bookings'] }}</h3>
                                            <p class="mb-0">Avg Monthly Bookings</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6>Booking Status Distribution</h6>
                                    @foreach($bookingAnalytics['status_distribution'] as $status => $count)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-capitalize">{{ $status }}</span>
                                            <span class="fw-bold">{{ $count }}</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $status == 'confirmed' ? 'success' : ($status == 'pending' ? 'warning' : ($status == 'checked_in' ? 'info' : ($status == 'checked_out' ? 'secondary' : 'danger'))) }}" 
                                                 style="width: {{ $bookingAnalytics['total_bookings'] > 0 ? ($count / $bookingAnalytics['total_bookings']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Occupancy & Performance Analytics ===== --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-trending-up me-1"></i> Occupancy Rate - {{ $year }}</h5>
                </div>
                <div class="card-body">
                    <div id="occupancy-chart" style="height: 250px;"></div>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <h4 class="text-info">{{ $occupancyAnalytics['annual_occupancy'] }}%</h4>
                            <small class="text-muted">Annual Occupancy</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ max($occupancyAnalytics['monthly_occupancy']) }}%</h4>
                            <small class="text-muted">Peak Month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-users me-1"></i> Guest Statistics - {{ $year }}</h5>
                </div>
                <div class="card-body">
                    <div id="guest-stats-chart" style="height: 250px;"></div>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <h4 class="text-success">{{ $userAnalytics['new_users_this_year'] }}</h4>
                            <small class="text-muted">New Guests</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary">{{ $bookingAnalytics['avg_monthly_bookings'] }}</h4>
                            <small class="text-muted">Avg Monthly</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info">{{ $userAnalytics['total_users'] }}</h4>
                            <small class="text-muted">Total Guests</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Lodge Performance ===== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-home me-1"></i> Lodge Performance Comparison - {{ $year }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($accommodationAnalytics['lodge_performance'] as $lodge => $stats)
                            <div class="col-md-6 mb-4">
                                <h6 class="border-bottom pb-2">{{ $lodge }}</h6>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h5 class="text-primary mb-0">{{ $stats['bookings'] }}</h5>
                                                <small class="text-muted">Bookings</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h5 class="text-success mb-0">{{ $stats['occupancy_rate'] }}%</h5>
                                                <small class="text-muted">Occupancy</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h5 class="text-info mb-0">{{ $stats['bookings'] > 0 ? round($stats['bookings'] / 12, 1) : 0 }}</h5>
                                                <small class="text-muted">Avg Monthly</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Room Type Performance --}}
                    <h6 class="mt-4 mb-3">Room Type Performance</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Room Type</th>
                                    <th>Total Rooms</th>
                                    <th>Total Beds</th>
                                    <th>Available Beds</th>
                                    <th>Utilization</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accommodationAnalytics['room_type_performance'] as $roomType)
                                    <tr>
                                        <td class="text-capitalize">{{ $roomType->type }}</td>
                                        <td>{{ $roomType->total }}</td>
                                        <td>{{ $roomType->total_beds }}</td>
                                        <td>{{ $roomType->available_beds }}</td>
                                        <td>
                                            @php
                                                $utilization = $roomType->total_beds > 0 ? 
                                                    (($roomType->total_beds - $roomType->available_beds) / $roomType->total_beds) * 100 : 0;
                                            @endphp
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $utilization }}%"></div>
                                            </div>
                                            <small>{{ round($utilization, 1) }}%</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== User & Reports Analytics ===== --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-users me-1"></i> User Registration Trend - {{ $year }}</h5>
                </div>
                <div class="card-body">
                    <div id="user-registration-chart" style="height: 250px;"></div>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <h4 class="text-success">{{ $userAnalytics['total_users'] }}</h4>
                            <small class="text-muted">Total Users</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-primary">{{ $userAnalytics['new_users_this_year'] }}</h4>
                            <small class="text-muted">New in {{ $year }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-alert-triangle me-1"></i> Reports Analytics - {{ $year }}</h5>
                </div>
                <div class="card-body">
                    <div id="reports-chart" style="height: 250px;"></div>
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <h4 class="text-success">{{ $reportAnalytics['resolution_rate'] }}%</h4>
                            <small class="text-muted">Resolution Rate</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info">{{ $reportAnalytics['avg_resolution_time'] ?? 0 }} days</h4>
                            <small class="text-muted">Avg Resolution Time</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ array_sum($reportAnalytics['monthly_reports']) }}</h4>
                            <small class="text-muted">Total Reports</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Seasonal Trends ===== --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="feather icon-sun me-1"></i> Seasonal Trends - {{ $year }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        @foreach($occupancyAnalytics['seasonal_trends'] as $quarter => $rate)
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card {{ $rate >= 70 ? 'bg-success text-white' : ($rate >= 50 ? 'bg-warning' : 'bg-light') }}">
                                    <div class="card-body">
                                        <h3 class="mb-1">{{ $rate }}%</h3>
                                        <p class="mb-0">{{ $quarter }} Occupancy</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Peak Booking Days --}}
                    <h6 class="mt-4 mb-3">Peak Booking Days</h6>
                    <div class="row">
                        @if(isset($bookingAnalytics['peak_days']) && $bookingAnalytics['peak_days']->count() > 0)
                            @foreach($bookingAnalytics['peak_days']->take(3) as $peakDay)
                                <div class="col-md-4 mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-capitalize">{{ $peakDay->day }}</span>
                                        <span class="badge bg-primary">{{ $peakDay->count }} bookings</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: {{ ($peakDay->count / $bookingAnalytics['total_bookings']) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-muted text-center">No peak day data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== Charts JS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize all charts
    initBookingTrendChart();
    initOccupancyChart();
    initGuestStatsChart();
    initUserRegistrationChart();
    initReportsChart();

    // Year filter
    document.getElementById('yearFilter').addEventListener('change', function() {
        window.location.href = '{{ route("admin.analytics") }}?year=' + this.value;
    });

    // Export functionality
    document.querySelectorAll('.export-analytics').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.getAttribute('data-type');
            exportAnalyticsData(type);
        });
    });

    function initBookingTrendChart() {
        const options = {
            chart: { 
                type: 'line', 
                height: 300, 
                toolbar: { show: true },
                zoom: { enabled: true }
            },
            series: [{
                name: 'Bookings',
                data: @json($bookingAnalytics['monthly_trend']['data'])
            }],
            xaxis: { 
                categories: @json($bookingAnalytics['monthly_trend']['labels']),
                title: { text: 'Months' }
            },
            yaxis: {
                title: { text: 'Number of Bookings' }
            },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#4e73df'],
            markers: { size: 5 },
            grid: {
                borderColor: '#f1f1f1',
            }
        };
        new ApexCharts(document.querySelector("#booking-trend-chart"), options).render();
    }

    function initOccupancyChart() {
        const options = {
            chart: { 
                type: 'area', 
                height: 250, 
                toolbar: { show: false }
            },
            series: [{
                name: 'Occupancy Rate (%)',
                data: @json($occupancyAnalytics['monthly_occupancy'])
            }],
            xaxis: { 
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: ['#36b9cc'],
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                }
            },
            yaxis: {
                min: 0,
                max: 100,
                labels: {
                    formatter: function(val) {
                        return val + '%';
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#occupancy-chart"), options).render();
    }

    function initGuestStatsChart() {
        const monthlyGuests = @json($userAnalytics['monthly_registrations']);
        const options = {
            chart: { 
                type: 'bar', 
                height: 250, 
                toolbar: { show: false }
            },
            series: [{
                name: 'New Guests',
                data: monthlyGuests
            }],
            xaxis: { 
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: ['#1cc88a'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                }
            },
            dataLabels: { enabled: false }
        };
        new ApexCharts(document.querySelector("#guest-stats-chart"), options).render();
    }

    function initUserRegistrationChart() {
        const options = {
            chart: { 
                type: 'line', 
                height: 250, 
                toolbar: { show: false }
            },
            series: [{
                name: 'New Users',
                data: @json($userAnalytics['monthly_registrations'])
            }],
            xaxis: { 
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: ['#f6c23e'],
            stroke: { curve: 'straight', width: 2 },
            markers: { size: 4 }
        };
        new ApexCharts(document.querySelector("#user-registration-chart"), options).render();
    }

    function initReportsChart() {
        const options = {
            chart: { 
                type: 'bar', 
                height: 250, 
                toolbar: { show: false }
            },
            series: [{
                name: 'Reports',
                data: @json($reportAnalytics['monthly_reports'])
            }],
            xaxis: { 
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            colors: ['#e74a3b'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                }
            }
        };
        new ApexCharts(document.querySelector("#reports-chart"), options).render();
    }

    function exportAnalyticsData(type) {
        fetch('{{ route("admin.analytics.export") }}?type=' + type + '&year=' + document.getElementById('yearFilter').value)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create and download CSV file
                    const csv = convertToCSV(data.data);
                    downloadCSV(csv, type + '-analytics-' + new Date().toISOString().split('T')[0] + '.csv');
                    
                    // Show success message
                    showToast('Success', type + ' data exported successfully', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'Failed to export data', 'error');
            });
    }

    function convertToCSV(obj) {
        const array = typeof obj != 'object' ? JSON.parse(obj) : obj;
        let str = '';
        
        // Add headers
        const headers = Object.keys(array);
        str += headers.join(',') + '\r\n';
        
        // Add data
        const values = Object.values(array).map(value => 
            typeof value === 'object' ? JSON.stringify(value) : value
        );
        str += values.join(',') + '\r\n';
        
        return str;
    }

    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', filename);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

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
});
</script>

<style>
@media print {
    .btn, .dropdown, .card-header .float-end {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        break-inside: avoid;
    }
    
    .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
    }
}

.card {
    margin-bottom: 1rem;
}

.widget-visitor-card {
    transition: transform 0.2s;
}
.widget-visitor-card:hover {
    transform: translateY(-2px);
}
</style>
@endsection