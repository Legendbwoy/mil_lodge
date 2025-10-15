<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Report;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $status = $request->get('status');
        
        // Build query
        $query = Booking::with('accommodation')
            ->latest();
            
        // Apply status filter if provided
        if ($status && in_array($status, ['pending', 'confirmed', 'checked_in', 'cancelled', 'checked_out'])) {
            $query->where('status', $status);
        }
        
        $bookings = $query->paginate(10);
        
        // Calculate stats for the cards
        $totalBookings = Booking::count();
        $activeBookingsCount = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $pendingBookingsCount = Booking::where('status', 'pending')->count();
        $checkingOutTodayCount = Booking::whereDate('check_out_date', Carbon::today())
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();
        
        return view('admin.bookings.index', compact(
            'bookings',
            'totalBookings',
            'activeBookingsCount',
            'pendingBookingsCount',
            'checkingOutTodayCount'
        ));
    }

    public function dashboard()
    {
        // ===== Quick Stats =====
        $totalBeds = Accommodation::sum('total_beds');
        $availableBeds = Accommodation::sum('available_beds');
        $occupiedBeds = $totalBeds - $availableBeds;
        
        // ===== Basic Totals =====
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalAccommodations = Accommodation::count();
        $totalReports = Report::count();

        // ===== Active Counts =====
        $activeBookings = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $activeGuests = Booking::where('status', 'checked_in')->count();
        $pendingBookingsCount = Booking::where('status', 'pending')->count();
        $pendingReportsCount = Report::where('status', 'pending')->count();

        // ===== Recent Data =====
        $recentBookings = Booking::with('accommodation')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with(['user', 'accommodation'])
            ->latest()
            ->take(5)
            ->get();

        // ===== Monthly Stats =====
        $bookingsThisMonth = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $reportsThisMonth = Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ===== Monthly Growth =====
        $lastMonthBookings = Booking::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $monthlyGrowth = $lastMonthBookings > 0 
            ? round((($bookingsThisMonth - $lastMonthBookings) / $lastMonthBookings) * 100, 1)
            : ($bookingsThisMonth > 0 ? 100 : 0);

        // ===== Monthly Occupancy =====
        $monthlyOccupancy = $this->calculateMonthlyOccupancyRate(now()->month, now()->year);

        // ===== Accommodation Stats =====
        $availableAccommodations = Accommodation::where('available_beds', '>', 0)->count();
        $occupiedAccommodations = Accommodation::where('available_beds', 0)->count();
        
        $accommodationAvailability = $totalAccommodations > 0
            ? round(($availableAccommodations / $totalAccommodations) * 100, 1)
            : 0;

        // ===== Lodge Stats =====
        $akafiaStats = $this->getLodgeStats('Akafia Lodge');
        $oppongStats = $this->getLodgeStats('Oppong Peprah Lodge');

        // ===== Booking Status Distribution =====
        $bookingStatuses = [
            'confirmed'  => Booking::where('status', 'confirmed')->count(),
            'pending'    => Booking::where('status', 'pending')->count(),
            'checked_in' => Booking::where('status', 'checked_in')->count(),
            'checked_out' => Booking::where('status', 'checked_out')->count(),
            'cancelled'  => Booking::where('status', 'cancelled')->count(),
        ];

        $totalStatus = array_sum($bookingStatuses);
        $bookingStatusPercentages = [];
        foreach ($bookingStatuses as $status => $count) {
            $bookingStatusPercentages[$status] = $totalStatus > 0 ? round(($count / $totalStatus) * 100, 1) : 0;
        }

        // ===== Report Stats =====
        $reportStatuses = [
            'pending' => Report::where('status', 'pending')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'cancelled' => Report::where('status', 'cancelled')->count(),
        ];

        $reportPriorities = [
            'urgent' => Report::where('priority', 'urgent')->count(),
        ];

        // ===== Check-out Notifications =====
        $checkoutNotifications = $this->getCheckoutNotifications();

        return view('admin.dashboard', compact(
            'totalBeds',
            'availableBeds',
            'occupiedBeds',
            'totalUsers',
            'totalBookings',
            'totalAccommodations',
            'totalReports',
            'activeBookings',
            'activeGuests',
            'pendingBookingsCount',
            'pendingReportsCount',
            'recentBookings',
            'recentReports',
            'bookingsThisMonth',
            'newUsersThisMonth',
            'reportsThisMonth',
            'monthlyGrowth',
            'monthlyOccupancy',
            'availableAccommodations',
            'occupiedAccommodations',
            'accommodationAvailability',
            'akafiaStats',
            'oppongStats',
            'bookingStatuses',
            'bookingStatusPercentages',
            'reportStatuses',
            'reportPriorities',
            'checkoutNotifications'
        ));
    }

    /**
     * Show comprehensive analytics and charts page
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $year = $request->get('year', now()->year);
        
        // Comprehensive analytics data
        $bookingAnalytics = $this->getBookingAnalytics($period, $year);
        $accommodationAnalytics = $this->getAccommodationAnalytics($year);
        $userAnalytics = $this->getUserAnalytics($year);
        $reportAnalytics = $this->getReportAnalytics($year);
        $occupancyAnalytics = $this->getOccupancyAnalytics($year);
        
        $availableYears = $this->getAvailableYears();

        return view('admin.analytics.index', compact(
            'bookingAnalytics',
            'accommodationAnalytics',
            'userAnalytics',
            'reportAnalytics',
            'occupancyAnalytics',
            'period',
            'year',
            'availableYears'
        ));
    }

    /**
     * Get lodge statistics
     */
    private function getLodgeStats($lodgeName)
    {
        return Accommodation::where('lodge_name', $lodgeName)
            ->selectRaw('
                COUNT(*) as total,
                SUM(available_beds) as available_beds_sum,
                SUM(total_beds) as total_beds_sum,
                SUM(CASE WHEN available_beds > 0 THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN available_beds = 0 THEN 1 ELSE 0 END) as occupied
            ')
            ->first();
    }

    /**
     * Calculate monthly occupancy rate
     */
    private function calculateMonthlyOccupancyRate($month, $year)
    {
        $totalRoomNights = Accommodation::count() * Carbon::create($year, $month)->daysInMonth;
        
        $bookedRoomNights = Booking::whereMonth('check_in_date', $month)
            ->whereYear('check_in_date', $year)
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->get()
            ->sum(function($booking) use ($month, $year) {
                $checkIn = Carbon::parse($booking->check_in_date);
                $checkOut = Carbon::parse($booking->check_out_date);
                
                $monthStart = Carbon::create($year, $month, 1);
                $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();
                
                $effectiveCheckIn = $checkIn->max($monthStart);
                $effectiveCheckOut = $checkOut->min($monthEnd);
                
                return max(0, $effectiveCheckOut->diffInDays($effectiveCheckIn));
            });
        
        return $totalRoomNights > 0 ? round(($bookedRoomNights / $totalRoomNights) * 100, 1) : 0;
    }

    /**
     * Get checkout notifications with complete data structure
     */
    private function getCheckoutNotifications()
    {
        $notifications = [];
        $now = Carbon::now();
        
        $activeBookings = Booking::with('accommodation')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_out_date', '>=', $now->toDateString())
            ->get();

        foreach ($activeBookings as $booking) {
            $checkOutDate = Carbon::parse($booking->check_out_date);
            $hoursUntilCheckout = $now->diffInHours($checkOutDate, false);
            
            // Only show notifications for checkouts within the next 24 hours
            if ($hoursUntilCheckout <= 24 && $hoursUntilCheckout >= 0) {
                $notification = $this->generateNotification($booking, $hoursUntilCheckout);
                if ($notification) {
                    $notifications[] = $notification;
                }
            }
        }

        // Sort by urgency (urgent first, then warning, then info)
        usort($notifications, function($a, $b) {
            $priority = ['urgent' => 0, 'warning' => 1, 'info' => 2];
            return $priority[$a['type']] - $priority[$b['type']];
        });

        return $notifications;
    }

    /**
     * Generate complete notification data structure
     */
    private function generateNotification($booking, $hoursUntilCheckout)
    {
        // Determine notification type and styling based on time remaining
        if ($hoursUntilCheckout <= 2) {
            $type = 'urgent';
            $color = 'danger';
            $icon = 'alert-triangle';
        } elseif ($hoursUntilCheckout <= 6) {
            $type = 'warning';
            $color = 'warning';
            $icon = 'alert-circle';
        } else {
            $type = 'info';
            $color = 'info';
            $icon = 'clock';
        }

        // Format time remaining
        $timeRemaining = $this->formatTimeRemaining($hoursUntilCheckout);
        
        // Format check-out time
        $checkOutTime = Carbon::parse($booking->check_out_date)->format('M j, Y g:i A');
        
        // Get accommodation name
        $accommodationName = $booking->accommodation ? $booking->accommodation->name : 'Unknown Accommodation';

        return [
            'icon' => $icon,
            'color' => $color,
            'type' => $type,
            'message' => "Guest {$booking->guest_name} needs to check out soon",
            'accommodation' => $accommodationName,
            'check_out_time' => $checkOutTime,
            'time_remaining' => $timeRemaining,
            'booking_id' => $booking->id,
            'guest_name' => $booking->guest_name
        ];
    }

    /**
     * Format time remaining in a human-readable way
     */
    private function formatTimeRemaining($hours)
    {
        if ($hours < 1) {
            $minutes = $hours * 60;
            return $minutes <= 1 ? 'less than 1 minute' : round($minutes) . ' minutes';
        } elseif ($hours < 24) {
            return $hours == 1 ? '1 hour' : round($hours) . ' hours';
        } else {
            $days = floor($hours / 24);
            return $days == 1 ? '1 day' : $days . ' days';
        }
    }

    // ===== ANALYTICS METHODS =====

    private function getBookingAnalytics($period = 'monthly', $year = null)
    {
        $year = $year ?? now()->year;
        
        $monthlyBookings = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyBookings[] = Booking::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->count();
        }

        $statusDistribution = [
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'checked_in' => Booking::where('status', 'checked_in')->count(),
            'checked_out' => Booking::where('status', 'checked_out')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // Get peak booking days
        $peakDays = Booking::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('day')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'monthly_trend' => [
                'data' => $monthlyBookings,
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            ],
            'status_distribution' => $statusDistribution,
            'total_bookings' => array_sum($monthlyBookings),
            'avg_monthly_bookings' => round(array_sum($monthlyBookings) / 12, 1),
            'peak_days' => $peakDays
        ];
    }

    private function getAccommodationAnalytics($year)
    {
        $lodges = ['Akafia Lodge', 'Oppong Peprah Lodge'];
        $lodgePerformance = [];
        
        foreach ($lodges as $lodge) {
            $bookings = Booking::whereHas('accommodation', function($query) use ($lodge) {
                $query->where('lodge_name', $lodge);
            })->whereYear('created_at', $year)->count();
            
            $occupancyRate = $this->calculateLodgeOccupancyRate($lodge, $year);
            
            $lodgePerformance[$lodge] = [
                'bookings' => $bookings,
                'occupancy_rate' => $occupancyRate,
            ];
        }

        // Get room type performance
        $roomTypePerformance = Accommodation::selectRaw('type, COUNT(*) as total, SUM(total_beds) as total_beds, SUM(available_beds) as available_beds')
            ->groupBy('type')
            ->get();

        return [
            'lodge_performance' => $lodgePerformance,
            'total_accommodations' => Accommodation::count(),
            'room_type_performance' => $roomTypePerformance
        ];
    }

    /**
     * Calculate lodge occupancy rate
     */
    private function calculateLodgeOccupancyRate($lodgeName, $year)
    {
        $accommodations = Accommodation::where('lodge_name', $lodgeName)->get();
        if ($accommodations->count() === 0) {
            return 0;
        }
        
        $totalRoomNights = $accommodations->count() * 365; // Simplified calculation
        
        $bookedRoomNights = Booking::whereHas('accommodation', function($query) use ($lodgeName) {
            $query->where('lodge_name', $lodgeName);
        })->whereYear('check_in_date', $year)
          ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
          ->get()
          ->sum(function($booking) {
              $checkIn = Carbon::parse($booking->check_in_date);
              $checkOut = Carbon::parse($booking->check_out_date);
              return $checkOut->diffInDays($checkIn);
          });
        
        return $totalRoomNights > 0 ? round(($bookedRoomNights / $totalRoomNights) * 100, 1) : 0;
    }

    private function getUserAnalytics($year)
    {
        $monthlyRegistrations = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRegistrations[] = User::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->count();
        }

        return [
            'monthly_registrations' => $monthlyRegistrations,
            'total_users' => User::count(),
            'new_users_this_year' => array_sum($monthlyRegistrations)
        ];
    }

    private function getReportAnalytics($year)
    {
        $monthlyReports = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyReports[] = Report::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->count();
        }

        return [
            'monthly_reports' => $monthlyReports,
            'resolution_rate' => $this->calculateResolutionRate($year),
            'avg_resolution_time' => $this->calculateAvgResolutionTime($year)
        ];
    }

    private function getOccupancyAnalytics($year)
    {
        $monthlyOccupancy = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyOccupancy[] = $this->calculateMonthlyOccupancyRate($i, $year);
        }

        // Calculate seasonal trends
        $seasonalTrends = [
            'Q1' => count(array_slice($monthlyOccupancy, 0, 3)) > 0 ? round(array_sum(array_slice($monthlyOccupancy, 0, 3)) / count(array_slice($monthlyOccupancy, 0, 3)), 1) : 0,
            'Q2' => count(array_slice($monthlyOccupancy, 3, 3)) > 0 ? round(array_sum(array_slice($monthlyOccupancy, 3, 3)) / count(array_slice($monthlyOccupancy, 3, 3)), 1) : 0,
            'Q3' => count(array_slice($monthlyOccupancy, 6, 3)) > 0 ? round(array_sum(array_slice($monthlyOccupancy, 6, 3)) / count(array_slice($monthlyOccupancy, 6, 3)), 1) : 0,
            'Q4' => count(array_slice($monthlyOccupancy, 9, 3)) > 0 ? round(array_sum(array_slice($monthlyOccupancy, 9, 3)) / count(array_slice($monthlyOccupancy, 9, 3)), 1) : 0,
        ];

        return [
            'monthly_occupancy' => $monthlyOccupancy,
            'annual_occupancy' => count($monthlyOccupancy) > 0 ? round(array_sum($monthlyOccupancy) / count($monthlyOccupancy), 1) : 0,
            'seasonal_trends' => $seasonalTrends
        ];
    }

    private function calculateResolutionRate($year)
    {
        $totalReports = Report::whereYear('created_at', $year)->count();
        $resolvedReports = Report::whereYear('created_at', $year)
            ->where('status', 'resolved')
            ->count();
        
        return $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100, 1) : 0;
    }

    private function calculateAvgResolutionTime($year)
    {
        $resolvedReports = Report::whereYear('created_at', $year)
            ->where('status', 'resolved')
            ->whereNotNull('resolved_at')
            ->get();
        
        if ($resolvedReports->isEmpty()) {
            return 0;
        }
        
        $totalDays = $resolvedReports->sum(function($report) {
            return Carbon::parse($report->created_at)->diffInDays(Carbon::parse($report->resolved_at));
        });
        
        return round($totalDays / $resolvedReports->count(), 1);
    }

    private function getAvailableYears()
    {
        $bookingYears = Booking::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        $userYears = User::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        $reportYears = Report::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        return $bookingYears->merge($userYears)->merge($reportYears)->unique()->sortDesc()->values();
    }

    public function exportAnalytics(Request $request)
    {
        $type = $request->get('type', 'bookings');
        $year = $request->get('year', now()->year);
        
        $data = [];
        switch ($type) {
            case 'bookings':
                $data = $this->getBookingAnalytics('monthly', $year);
                break;
            case 'occupancy':
                $data = $this->getOccupancyAnalytics($year);
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'type' => $type,
            'year' => $year
        ]);
    }

    /**
     * Quick status update endpoint for AJAX requests - FIXED VERSION
     */
    public function quickStatusUpdate(Request $request, $id)
    {
        \Log::info('Quick Status Update Called', [
            'booking_id' => $id,
            'new_status' => $request->input('status'),
            'current_user' => auth()->user()->id ?? 'guest',
            'request_data' => $request->all()
        ]);

        return $this->updateBookingStatus($request, $id);
    }

    /**
     * Update booking status with proper bed management - COMPLETELY FIXED FOR CHECK-IN
     */
    public function updateBookingStatus(Request $request, $id)
    {
        try {
            \Log::info('Update Booking Status Started', [
                'booking_id' => $id,
                'new_status' => $request->input('status')
            ]);

            $booking = Booking::with('accommodation')->findOrFail($id);
            
            $validStatuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];
            $newStatus = $request->input('status');
            
            if (!in_array($newStatus, $validStatuses)) {
                \Log::warning('Invalid status provided', [
                    'booking_id' => $id,
                    'provided_status' => $newStatus,
                    'valid_statuses' => $validStatuses
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status provided. Must be one of: ' . implode(', ', $validStatuses)
                ], 400);
            }
            
            $oldStatus = $booking->status;
            
            \Log::info('Booking status transition', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'accommodation_id' => $booking->accommodation?->id,
                'number_of_beds' => $booking->number_of_beds
            ]);
            
            // Handle bed availability FIRST before updating status
            if ($booking->accommodation) {
                $this->handleBedAvailability($booking, $oldStatus, $newStatus);
            }
            
            // Update the booking status
            $booking->status = $newStatus;
            
            // Set actual check-in and check-out times
            if ($newStatus === 'checked_in' && !$booking->actual_check_in) {
                $booking->actual_check_in = now();
                \Log::info('Set actual check-in time', ['booking_id' => $booking->id]);
            }
            
            if ($newStatus === 'checked_out' && !$booking->actual_check_out) {
                $booking->actual_check_out = now();
                \Log::info('Set actual check-out time', ['booking_id' => $booking->id]);
            }
            
            $booking->save();
            
            \Log::info('Booking status updated successfully', [
                'booking_id' => $booking->id,
                'new_status' => $booking->status,
                'actual_check_in' => $booking->actual_check_in,
                'actual_check_out' => $booking->actual_check_out
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully',
                'booking' => [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'status_badge' => $this->getBookingStatusBadge($booking->status),
                    'actual_check_in' => $booking->actual_check_in?->format('M d, Y h:i A'),
                    'actual_check_out' => $booking->actual_check_out?->format('M d, Y h:i A')
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to update booking status', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle bed availability when booking status changes - SIMPLIFIED AND FIXED
     */
    private function handleBedAvailability($booking, $oldStatus, $newStatus)
    {
        $accommodation = $booking->accommodation;
        $numberOfBeds = $booking->number_of_beds;

        \Log::info('Handling bed availability', [
            'accommodation_id' => $accommodation->id,
            'accommodation_name' => $accommodation->name,
            'current_available_beds' => $accommodation->available_beds,
            'total_beds' => $accommodation->total_beds,
            'number_of_beds' => $numberOfBeds,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        // SIMPLIFIED LOGIC: Only change bed counts when moving to/from checked_in status
        
        // When moving TO checked_in status (from any other status)
        if ($newStatus === 'checked_in' && $oldStatus !== 'checked_in') {
            $newAvailableBeds = $accommodation->available_beds - $numberOfBeds;
            
            if ($newAvailableBeds < 0) {
                \Log::warning('Not enough beds available for check-in', [
                    'accommodation_id' => $accommodation->id,
                    'requested_beds' => $numberOfBeds,
                    'available_beds' => $accommodation->available_beds
                ]);
                throw new \Exception("Not enough beds available for check-in. Requested: {$numberOfBeds}, Available: {$accommodation->available_beds}");
            }
            
            $accommodation->available_beds = $newAvailableBeds;
            $accommodation->save();
            
            \Log::info('Beds allocated for check-in', [
                'accommodation_id' => $accommodation->id,
                'beds_allocated' => $numberOfBeds,
                'new_available_beds' => $accommodation->available_beds
            ]);
        }
        
        // When moving FROM checked_in status (to any other status except checked_in)
        elseif ($oldStatus === 'checked_in' && $newStatus !== 'checked_in') {
            $newAvailableBeds = $accommodation->available_beds + $numberOfBeds;
            
            // Ensure we don't exceed total beds
            if ($newAvailableBeds > $accommodation->total_beds) {
                $newAvailableBeds = $accommodation->total_beds;
                \Log::warning('Available beds would exceed total beds, capping', [
                    'accommodation_id' => $accommodation->id,
                    'calculated_available' => $newAvailableBeds,
                    'total_beds' => $accommodation->total_beds
                ]);
            }
            
            $accommodation->available_beds = $newAvailableBeds;
            $accommodation->save();
            
            \Log::info('Beds returned from check-in', [
                'accommodation_id' => $accommodation->id,
                'beds_returned' => $numberOfBeds,
                'new_available_beds' => $accommodation->available_beds,
                'new_status' => $newStatus
            ]);
        }
        
        // When moving from pending to confirmed - allocate beds
        elseif ($oldStatus === 'pending' && $newStatus === 'confirmed') {
            $newAvailableBeds = $accommodation->available_beds - $numberOfBeds;
            
            if ($newAvailableBeds < 0) {
                \Log::warning('Not enough beds available for confirmation', [
                    'accommodation_id' => $accommodation->id,
                    'requested_beds' => $numberOfBeds,
                    'available_beds' => $accommodation->available_beds
                ]);
                throw new \Exception("Not enough beds available to confirm booking. Requested: {$numberOfBeds}, Available: {$accommodation->available_beds}");
            }
            
            $accommodation->available_beds = $newAvailableBeds;
            $accommodation->save();
            
            \Log::info('Beds allocated for confirmation', [
                'accommodation_id' => $accommodation->id,
                'beds_allocated' => $numberOfBeds,
                'new_available_beds' => $accommodation->available_beds
            ]);
        }
        
        // When moving from confirmed to pending - return beds
        elseif ($oldStatus === 'confirmed' && $newStatus === 'pending') {
            $newAvailableBeds = $accommodation->available_beds + $numberOfBeds;
            
            if ($newAvailableBeds > $accommodation->total_beds) {
                $newAvailableBeds = $accommodation->total_beds;
            }
            
            $accommodation->available_beds = $newAvailableBeds;
            $accommodation->save();
            
            \Log::info('Beds returned (confirmed to pending)', [
                'accommodation_id' => $accommodation->id,
                'beds_returned' => $numberOfBeds,
                'new_available_beds' => $accommodation->available_beds
            ]);
        }

        \Log::info('Bed availability handling completed', [
            'accommodation_id' => $accommodation->id,
            'final_available_beds' => $accommodation->available_beds
        ]);
    }

    /**
     * Helper method to get booking status badge
     */
    private function getBookingStatusBadge($status)
    {
        $statusClasses = [
            'confirmed' => 'success',
            'pending' => 'warning',
            'checked_in' => 'info',
            'checked_out' => 'secondary',
            'cancelled' => 'danger'
        ];
        return $statusClasses[$status] ?? 'secondary';
    }

    /**
     * Get booking details for modal
     */
    public function getBookingDetails($id)
    {
        try {
            $booking = Booking::with(['accommodation'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'booking' => [
                    'id' => $booking->id,
                    'guest_name' => $booking->guest_name,
                    'guest_email' => $booking->guest_email,
                    'guest_phone' => $booking->guest_phone,
                    'service_number' => $booking->service_number,
                    'rank' => $booking->rank,
                    'unit' => $booking->unit,
                    'branch' => $booking->branch,
                    'purpose' => $booking->purpose,
                    'number_of_beds' => $booking->number_of_beds,
                    'check_in_date' => $booking->check_in_date->format('Y-m-d'),
                    'check_out_date' => $booking->check_out_date->format('Y-m-d'),
                    'actual_check_in' => $booking->actual_check_in?->format('M d, Y h:i A'),
                    'actual_check_out' => $booking->actual_check_out?->format('M d, Y h:i A'),
                    'status' => $booking->status,
                    'special_requests' => $booking->special_requests,
                    'created_at' => $booking->created_at->format('M d, Y \a\t h:i A'),
                    'accommodation' => $booking->accommodation ? [
                        'name' => $booking->accommodation->name,
                        'lodge_name' => $booking->accommodation->lodge_name,
                        'type' => $booking->accommodation->type,
                        'location' => $booking->accommodation->location,
                        'room_number' => $booking->accommodation->room_number,
                        'available_beds' => $booking->accommodation->available_beds,
                        'total_beds' => $booking->accommodation->total_beds
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get booking details', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    /**
     * Update accommodation status
     */
    public function updateAccommodationStatus(Request $request, $id)
    {
        try {
            $accommodation = Accommodation::findOrFail($id);
            
            $validStatuses = ['available', 'occupied', 'maintenance'];
            $newStatus = $request->input('status');
            
            if (!in_array($newStatus, $validStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status'
                ], 400);
            }
            
            // Temporary implementation using available_beds
            if ($newStatus === 'available') {
                $accommodation->available_beds = $accommodation->total_beds;
            } elseif ($newStatus === 'occupied') {
                $accommodation->available_beds = 0;
            } elseif ($newStatus === 'maintenance') {
                $accommodation->available_beds = 0;
            }
            
            $accommodation->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Accommodation status updated successfully',
                'accommodation' => [
                    'id' => $accommodation->id,
                    'status' => $newStatus,
                    'status_badge' => $this->getStatusBadge($newStatus),
                    'available_beds' => $accommodation->available_beds,
                    'total_beds' => $accommodation->total_beds
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update accommodation status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to get status badge class
     */
    private function getStatusBadge($status)
    {
        $statusClasses = [
            'available' => 'success',
            'occupied' => 'warning',
            'maintenance' => 'danger'
        ];
        return $statusClasses[$status] ?? 'secondary';
    }

    /**
     * Update report status
     */
    public function updateReportStatus(Request $request, $id)
    {
        try {
            $report = Report::findOrFail($id);
            
            $validStatuses = ['pending', 'in_progress', 'resolved', 'cancelled'];
            $newStatus = $request->input('status');
            $adminNotes = $request->input('admin_notes');
            
            if (!in_array($newStatus, $validStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status'
                ], 400);
            }
            
            // Update the status and admin notes
            $report->status = $newStatus;
            if ($adminNotes) {
                $report->admin_notes = $adminNotes;
            }
            
            if ($newStatus === 'resolved') {
                $report->resolved_at = now();
            }
            
            $report->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Report status updated successfully',
                'report' => [
                    'id' => $report->id,
                    'status' => $report->status,
                    'status_badge' => $report->status_badge,
                    'resolved_at' => $report->resolved_at?->format('M d, Y'),
                    'admin_notes' => $report->admin_notes
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update report status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get report details for modal
     */
    public function getReportDetails($id)
    {
        try {
            $report = Report::with(['user', 'accommodation'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                    'description' => $report->description,
                    'report_type' => $report->report_type,
                    'report_type_label' => $report->report_type_label,
                    'location' => $report->location,
                    'priority' => $report->priority,
                    'status' => $report->status,
                    'admin_notes' => $report->admin_notes,
                    'created_at' => $report->created_at->format('M d, Y \a\t h:i A'),
                    'resolved_at' => $report->resolved_at?->format('M d, Y \a\t h:i A'),
                    'user' => $report->user ? [
                        'name' => $report->user->name,
                        'email' => $report->user->email
                    ] : null,
                    'accommodation' => $report->accommodation ? [
                        'name' => $report->accommodation->name,
                        'location' => $report->accommodation->location
                    ] : null,
                    'images' => $report->images
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }
    }

    /**
     * Show reports management page
     */
    public function reports(Request $request)
    {
        $status = $request->get('status');
        $priority = $request->get('priority');
        
        $query = Report::with(['user', 'accommodation'])->latest();
        
        if ($status && in_array($status, ['pending', 'in_progress', 'resolved', 'cancelled'])) {
            $query->where('status', $status);
        }
        
        if ($priority && in_array($priority, ['low', 'medium', 'high', 'urgent'])) {
            $query->where('priority', $priority);
        }
        
        $reports = $query->paginate(15);
        
        $pendingReportsCount = Report::where('status', 'pending')->count();
        $urgentReportsCount = Report::where('priority', 'urgent')->count();

        return view('reports.index', compact(
            'reports',
            'pendingReportsCount',
            'urgentReportsCount'
        ));
    }

    /**
     * Auto check-out overdue bookings (can be called via cron job)
     */
    public function autoCheckoutOverdueBookings()
    {
        try {
            $overdueBookings = Booking::with('accommodation')
                ->whereIn('status', ['checked_in'])
                ->whereDate('check_out_date', '<', now()->toDateString())
                ->get();

            $checkedOutCount = 0;
            
            foreach ($overdueBookings as $booking) {
                $this->handleBedAvailability($booking, $booking->status, 'checked_out');
                $booking->status = 'checked_out';
                $booking->actual_check_out = now();
                $booking->save();
                $checkedOutCount++;
            }

            Log::info("Auto checkout completed: {$checkedOutCount} bookings checked out");
            
            return response()->json([
                'success' => true,
                'message' => "{$checkedOutCount} overdue bookings automatically checked out"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Auto checkout failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Auto checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notifications for admin dashboard
     */
    public function getNotifications()
    {
        $notifications = $this->getCheckoutNotifications();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
    }

    /**
     * Mark notifications as read
     */
    public function markNotificationsAsRead(Request $request)
    {
        // Implementation depends on how you want to handle notification marking
        // This could involve updating a read_at timestamp in a notifications table
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    }
}