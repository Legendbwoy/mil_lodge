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
        if ($status && in_array($status, ['pending', 'confirmed', 'checked_in', 'cancelled'])) {
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
        $totalBeds = Accommodation::sum('total_beds');
        $availableBeds = Accommodation::sum('available_beds');
        $occupiedBeds = $totalBeds - $availableBeds;
        
        // Lodge-specific stats
        $akafiaStats = Accommodation::where('lodge_name', 'Akafia Lodge')
            ->selectRaw('SUM(total_beds) as total, SUM(available_beds) as available')
            ->first();
            
        $oppongStats = Accommodation::where('lodge_name', 'Oppong Peprah Lodge')
            ->selectRaw('SUM(total_beds) as total, SUM(available_beds) as available')
            ->first();

        // ===== Totals =====
        $totalUsers          = User::count();
        $totalBookings       = Booking::count();
        $totalAccommodations = Accommodation::count();
        $totalRevenue        = Booking::where('status', '!=', 'cancelled')->sum('total_amount');

        // ===== Pending Counts for Navigation =====
        $pendingBookingsCount = Booking::where('status', 'pending')->count();
        $pendingReportsCount = Report::where('status', 'pending')->count();

        // ===== Recent Bookings =====
        $recentBookings = Booking::with('accommodation')
            ->latest()
            ->take(5)
            ->get();

        // ===== Booking Status Distribution =====
        $bookingStatuses = [
            'confirmed'  => Booking::where('status', 'confirmed')->count(),
            'pending'    => Booking::where('status', 'pending')->count(),
            'checked_in' => Booking::where('status', 'checked_in')->count(),
            'cancelled'  => Booking::where('status', 'cancelled')->count(),
        ];
        $totalStatus = array_sum($bookingStatuses);
        $bookingStatusPercentages = [];
        foreach ($bookingStatuses as $status => $count) {
            $bookingStatusPercentages[$status] =
                $totalStatus > 0 ? round(($count / $totalStatus) * 100, 1) : 0;
        }

        // ===== Accommodation Availability =====
        // Temporary fix: Calculate status based on available_beds
        $availableAccommodations = Accommodation::where('available_beds', '>', 0)->count();
        $occupiedAccommodations  = Accommodation::where('available_beds', 0)->count();
        $maintenanceAccommodations = 0; // Will be implemented after migration
        
        $accommodationAvailability = $totalAccommodations > 0
            ? round(($availableAccommodations / $totalAccommodations) * 100, 1)
            : 0;
            
        $maintenancePercentage = 0; // Will be implemented after migration

        // ===== Monthly Stats =====
        $monthlyRevenue = Booking::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $bookingsThisMonth = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ===== Revenue Chart Data (last 6 months) =====
        $values = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $values[] = Booking::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
            $months[] = $month->format('M Y');
        }
        $revenueData = [
            'values' => $values,
            'months' => $months
        ];

        // ===== Reports Analytics =====
        $totalReports = Report::count();
        $reportsThisMonth = Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $reportStatuses = [
            'pending' => Report::where('status', 'pending')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'cancelled' => Report::where('status', 'cancelled')->count(),
        ];

        $reportTypes = [
            'amenity_issue' => Report::where('report_type', 'amenity_issue')->count(),
            'repair' => Report::where('report_type', 'repair')->count(),
            'renovation' => Report::where('report_type', 'renovation')->count(),
        ];

        $reportPriorities = [
            'low' => Report::where('priority', 'low')->count(),
            'medium' => Report::where('priority', 'medium')->count(),
            'high' => Report::where('priority', 'high')->count(),
            'urgent' => Report::where('priority', 'urgent')->count(),
        ];

        // ===== Recent Reports =====
        $recentReports = Report::with(['user', 'accommodation'])
            ->latest()
            ->take(5)
            ->get();

        // ===== Accommodations for Status Management =====
        $accommodations = Accommodation::with(['bookings' => function($query) {
            $query->whereIn('status', ['confirmed', 'checked_in']);
        }])->get();

        // Enhanced lodge stats with status breakdown (temporary - based on available beds)
        $akafiaStats = Accommodation::where('lodge_name', 'Akafia Lodge')
            ->selectRaw('
                COUNT(*) as total,
                SUM(available_beds) as available_beds_sum,
                SUM(total_beds) as total_beds_sum,
                SUM(CASE WHEN available_beds > 0 THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN available_beds = 0 THEN 1 ELSE 0 END) as occupied,
                0 as maintenance
            ')
            ->first();
            
        $oppongStats = Accommodation::where('lodge_name', 'Oppong Peprah Lodge')
            ->selectRaw('
                COUNT(*) as total,
                SUM(available_beds) as available_beds_sum,
                SUM(total_beds) as total_beds_sum,
                SUM(CASE WHEN available_beds > 0 THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN available_beds = 0 THEN 1 ELSE 0 END) as occupied,
                0 as maintenance
            ')
            ->first();

        // ===== Check-out Notifications =====
        $checkoutNotifications = $this->getCheckoutNotifications();

        return view('admin.dashboard', compact(
            'totalBeds',
            'availableBeds',
            'occupiedBeds',
            'akafiaStats',
            'oppongStats',
            'totalUsers',
            'totalBookings',
            'totalAccommodations',
            'totalRevenue',
            'recentBookings',
            'bookingStatuses',
            'bookingStatusPercentages',
            'availableAccommodations',
            'occupiedAccommodations',
            'maintenanceAccommodations',
            'maintenancePercentage',
            'accommodationAvailability',
            'monthlyRevenue',
            'newUsersThisMonth',
            'bookingsThisMonth',
            'revenueData',
            'totalReports',
            'reportsThisMonth',
            'reportStatuses',
            'reportTypes',
            'reportPriorities',
            'recentReports',
            'pendingBookingsCount', 
            'pendingReportsCount',
            'accommodations',
            'checkoutNotifications',
            'akafiaStats',
            'oppongStats',
        ));
    }

    /**
     * Get checkout notifications for upcoming checkouts
     */
    private function getCheckoutNotifications()
    {
        $notifications = [];
        $now = Carbon::now();
        
        // Get all active bookings (confirmed or checked_in)
        $activeBookings = Booking::with('accommodation')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_out_date', '>=', $now->toDateString())
            ->get();

        foreach ($activeBookings as $booking) {
            $checkOutDate = Carbon::parse($booking->check_out_date);
            $timeUntilCheckout = $now->diff($checkOutDate);
            
            // Check if checkout is within 24 hours
            if ($timeUntilCheckout->days == 0 && $timeUntilCheckout->h < 24) {
                // Check if checkout is within 1 hour
                if ($timeUntilCheckout->h <= 1 && $timeUntilCheckout->i >= 0) {
                    $notifications[] = [
                        'type' => 'urgent',
                        'message' => "Booking #{$booking->id} for {$booking->guest_name} is checking out in less than 1 hour!",
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'check_out_time' => $checkOutDate->format('M d, Y h:i A'),
                        'time_remaining' => $timeUntilCheckout->h . ' hours ' . $timeUntilCheckout->i . ' minutes',
                        'accommodation' => $booking->accommodation->name ?? 'N/A',
                        'icon' => 'alert-triangle',
                        'color' => 'danger'
                    ];
                } 
                // Check if checkout is within 24 hours but more than 1 hour
                else {
                    $notifications[] = [
                        'type' => 'warning',
                        'message' => "Booking #{$booking->id} for {$booking->guest_name} is checking out today at {$checkOutDate->format('h:i A')}",
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'check_out_time' => $checkOutDate->format('M d, Y h:i A'),
                        'time_remaining' => $timeUntilCheckout->h . ' hours ' . $timeUntilCheckout->i . ' minutes',
                        'accommodation' => $booking->accommodation->name ?? 'N/A',
                        'icon' => 'clock',
                        'color' => 'warning'
                    ];
                }
            }
            // Check if checkout is tomorrow (24-48 hours)
            elseif ($timeUntilCheckout->days == 1 && $timeUntilCheckout->h < 24) {
                $notifications[] = [
                    'type' => 'info',
                    'message' => "Booking #{$booking->id} for {$booking->guest_name} is checking out tomorrow at {$checkOutDate->format('h:i A')}",
                    'booking_id' => $booking->id,
                    'guest_name' => $booking->guest_name,
                    'check_out_time' => $checkOutDate->format('M d, Y h:i A'),
                    'time_remaining' => '1 day ' . $timeUntilCheckout->h . ' hours',
                    'accommodation' => $booking->accommodation->name ?? 'N/A',
                    'icon' => 'info',
                    'color' => 'info'
                ];
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
     * AJAX endpoint to get real-time notifications
     */
    public function getNotifications()
    {
        try {
            $notifications = $this->getCheckoutNotifications();
            $unreadCount = count($notifications);

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications'
            ], 500);
        }
    }

    /**
     * Mark notifications as read
     */
    public function markNotificationsAsRead()
    {
        // In a real application, you might want to track read status in database
        // For now, we'll just return success since notifications are real-time
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    }

    // Method to update accommodation status (temporary version without status column)
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
            // After migration, we'll use the status column
            if ($newStatus === 'available') {
                $accommodation->available_beds = $accommodation->total_beds;
            } elseif ($newStatus === 'occupied') {
                $accommodation->available_beds = 0;
            } elseif ($newStatus === 'maintenance') {
                $accommodation->available_beds = 0;
                // Note: After migration, we'll set status = 'maintenance'
            }
            
            $accommodation->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Accommodation status updated successfully',
                'accommodation' => [
                    'id' => $accommodation->id,
                    'status' => $newStatus, // Return the intended status
                    'status_badge' => $this->getStatusBadge($newStatus), // Helper method
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

    // Helper method to get status badge class
    private function getStatusBadge($status)
    {
        $statusClasses = [
            'available' => 'success',
            'occupied' => 'warning',
            'maintenance' => 'danger'
        ];
        return $statusClasses[$status] ?? 'secondary';
    }

    // Method to update booking status
    public function updateBookingStatus(Request $request, $id)
    {
        try {
            $booking = Booking::with('accommodation')->findOrFail($id);
            
            $validStatuses = ['pending', 'confirmed', 'checked_in', 'cancelled'];
            $newStatus = $request->input('status');
            
            if (!in_array($newStatus, $validStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status'
                ], 400);
            }
            
            // Update the booking status
            $oldStatus = $booking->status;
            $booking->status = $newStatus;
            $booking->save();
            
            // Update accommodation available beds if status changed to/from checked_in
            if ($booking->accommodation && ($oldStatus === 'checked_in' || $newStatus === 'checked_in')) {
                $this->updateAccommodationBeds($booking->accommodation, $oldStatus, $newStatus, $booking->number_of_beds);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully',
                'booking' => [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'status_badge' => $this->getBookingStatusBadge($booking->status)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper method to get booking status badge
    private function getBookingStatusBadge($status)
    {
        $statusClasses = [
            'confirmed' => 'success',
            'pending' => 'warning',
            'checked_in' => 'info',
            'cancelled' => 'danger'
        ];
        return $statusClasses[$status] ?? 'secondary';
    }

    // Helper method to update accommodation beds based on booking status changes
    private function updateAccommodationBeds($accommodation, $oldStatus, $newStatus, $numberOfBeds)
    {
        if ($oldStatus === 'checked_in' && $newStatus !== 'checked_in') {
            // Guest checked out - free up beds
            $accommodation->available_beds += $numberOfBeds;
        } elseif ($newStatus === 'checked_in' && $oldStatus !== 'checked_in') {
            // Guest checked in - occupy beds
            $accommodation->available_beds = max(0, $accommodation->available_beds - $numberOfBeds);
        }
        
        $accommodation->save();
    }

    // Method to get booking details for modal
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
                    'number_of_beds' => $booking->number_of_beds,
                    'check_in_date' => $booking->check_in_date->format('Y-m-d'),
                    'check_out_date' => $booking->check_out_date->format('Y-m-d'),
                    'total_amount' => $booking->total_amount,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'payment_method' => $booking->payment_method,
                    'special_requests' => $booking->special_requests,
                    'created_at' => $booking->created_at->format('M d, Y \a\t h:i A'),
                    'accommodation' => $booking->accommodation ? [
                        'name' => $booking->accommodation->name,
                        'type' => $booking->accommodation->type,
                        'location' => $booking->accommodation->location,
                        'room_number' => $booking->accommodation->room_number
                    ] : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }

    // Method to update report status
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

    // Method to get report details for modal
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
}