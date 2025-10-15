<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('accommodation')->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        // Fetch available accommodations with beds
        $accommodations = Accommodation::where('available_beds', '>', 0)
            ->where('status', 'available')
            ->get();
            
        return view('admin.bookings.create', compact('accommodations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'service_number' => 'required|string|max:255',
            'rank' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'number_of_beds' => 'required|integer|min:1',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Check accommodation availability
            $accommodation = Accommodation::findOrFail($validated['accommodation_id']);
            
            if ($accommodation->available_beds < $validated['number_of_beds']) {
                return back()->withErrors([
                    'number_of_beds' => 'Not enough beds available. Only ' . $accommodation->available_beds . ' beds left.'
                ])->withInput();
            }

            // Calculate total amount (you can modify this logic based on your pricing)
            $nights = ceil((strtotime($validated['check_out_date']) - strtotime($validated['check_in_date'])) / (60 * 60 * 24));
            $pricePerBed = $accommodation->price_per_bed ?? 50; // Default price if not set
            $totalAmount = $validated['number_of_beds'] * $nights * $pricePerBed;

            // Create booking
            $booking = Booking::create([
                'accommodation_id' => $validated['accommodation_id'],
                'service_number' => $validated['service_number'],
                'rank' => $validated['rank'],
                'unit' => $validated['unit'],
                'branch' => $validated['branch'],
                'purpose' => $validated['purpose'],
                'guest_name' => $validated['guest_name'],
                'guest_phone' => $validated['guest_phone'],
                'guest_email' => $validated['guest_email'],
                'number_of_beds' => $validated['number_of_beds'],
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'special_requests' => $validated['special_requests'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Update accommodation available beds
            $accommodation->available_beds -= $validated['number_of_beds'];
            $accommodation->save();

            DB::commit();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create booking: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Booking $booking)
    {
        $booking->load('accommodation');
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $accommodations = Accommodation::where('available_beds', '>', 0)
            ->orWhere('id', $booking->accommodation_id)
            ->get();
            
        return view('admin.bookings.edit', compact('booking', 'accommodations'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'service_number' => 'required|string|max:255',
            'rank' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'number_of_beds' => 'required|integer|min:1',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $oldAccommodationId = $booking->accommodation_id;
            $oldNumberOfBeds = $booking->number_of_beds;

            // Check if accommodation or number of beds changed
            if ($validated['accommodation_id'] != $oldAccommodationId || $validated['number_of_beds'] != $oldNumberOfBeds) {
                
                // Return beds to old accommodation
                $oldAccommodation = Accommodation::find($oldAccommodationId);
                if ($oldAccommodation) {
                    $oldAccommodation->available_beds += $oldNumberOfBeds;
                    $oldAccommodation->save();
                }

                // Check new accommodation availability
                $newAccommodation = Accommodation::findOrFail($validated['accommodation_id']);
                
                if ($newAccommodation->available_beds < $validated['number_of_beds']) {
                    return back()->withErrors([
                        'number_of_beds' => 'Not enough beds available. Only ' . $newAccommodation->available_beds . ' beds left.'
                    ])->withInput();
                }

                // Reserve beds in new accommodation
                $newAccommodation->available_beds -= $validated['number_of_beds'];
                $newAccommodation->save();
            }

            // Calculate total amount if dates or beds changed
            if ($validated['check_in_date'] != $booking->check_in_date || 
                $validated['check_out_date'] != $booking->check_out_date ||
                $validated['number_of_beds'] != $oldNumberOfBeds) {
                
                $nights = ceil((strtotime($validated['check_out_date']) - strtotime($validated['check_in_date'])) / (60 * 60 * 24));
                $accommodation = Accommodation::find($validated['accommodation_id']);
                $pricePerBed = $accommodation->price_per_bed ?? 50;
                $validated['total_amount'] = $validated['number_of_beds'] * $nights * $pricePerBed;
            }

            // Update booking
            $booking->update($validated);

            DB::commit();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update booking: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Return beds to accommodation
            $accommodation = $booking->accommodation;
            if ($accommodation) {
                $accommodation->available_beds += $booking->number_of_beds;
                
                // Ensure we don't exceed total beds
                if ($accommodation->available_beds > $accommodation->total_beds) {
                    $accommodation->available_beds = $accommodation->total_beds;
                }
                
                $accommodation->save();
            }

            // Delete the booking
            $booking->delete();

            DB::commit();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    /**
     * Quick status update for AJAX requests
     */
    public function quickStatusUpdate(Request $request, $id)
    {
        try {
            $booking = Booking::with('accommodation')->findOrFail($id);
            
            $validStatuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];
            $newStatus = $request->input('status');
            
            if (!in_array($newStatus, $validStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status'
                ], 400);
            }
            
            $oldStatus = $booking->status;
            
            // Handle bed availability based on status changes
            if ($booking->accommodation) {
                $this->handleBedAvailability($booking, $oldStatus, $newStatus);
            }
            
            // Update the booking status
            $booking->status = $newStatus;
            
            // Set actual check-in and check-out times
            if ($newStatus === 'checked_in' && !$booking->actual_check_in) {
                $booking->actual_check_in = now();
            }
            
            if ($newStatus === 'checked_out' && !$booking->actual_check_out) {
                $booking->actual_check_out = now();
            }
            
            $booking->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully',
                'booking' => [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'status_badge' => $this->getBookingStatusBadge($booking->status),
                    'actual_check_in' => $booking->actual_check_in,
                    'actual_check_out' => $booking->actual_check_out
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle bed availability when booking status changes
     */
    private function handleBedAvailability($booking, $oldStatus, $newStatus)
    {
        $accommodation = $booking->accommodation;
        $numberOfBeds = $booking->number_of_beds;

        // When guest checks in, reduce available beds
        if ($newStatus === 'checked_in' && $oldStatus !== 'checked_in') {
            $accommodation->available_beds = max(0, $accommodation->available_beds - $numberOfBeds);
            $accommodation->save();
        }
        
        // When guest checks out or cancels, return beds to availability
        elseif (($newStatus === 'checked_out' || $newStatus === 'cancelled') && 
                ($oldStatus === 'checked_in' || $oldStatus === 'confirmed')) {
            $accommodation->available_beds += $numberOfBeds;
            
            // Ensure we don't exceed total beds
            if ($accommodation->available_beds > $accommodation->total_beds) {
                $accommodation->available_beds = $accommodation->total_beds;
            }
            
            $accommodation->save();
        }
        
        // When moving from confirmed to any other status (except checked_in), return beds
        elseif ($oldStatus === 'confirmed' && $newStatus !== 'checked_in') {
            $accommodation->available_beds += $numberOfBeds;
            
            // Ensure we don't exceed total beds
            if ($accommodation->available_beds > $accommodation->total_beds) {
                $accommodation->available_beds = $accommodation->total_beds;
            }
            
            $accommodation->save();
        }
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
                    'total_amount' => $booking->total_amount,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'payment_method' => $booking->payment_method,
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
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
    }
}