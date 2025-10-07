<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_beds' => 'required|integer|min:1|max:10', // Limit beds per booking
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'guest_phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
            // Add validation for new military fields
            'service_number' => 'required|string|max:50',
            'rank' => 'required|string|max:50',
            'unit' => 'required|string|max:100',
            'branch' => 'required|string|max:50',
            'purpose' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $accommodation = Accommodation::findOrFail($request->accommodation_id);

            // Check bed availability
            if (!$accommodation->isAvailableForBeds($request->number_of_beds, $request->check_in_date, $request->check_out_date)) {
                return redirect()->back()->with('error', 'Not enough beds available for the selected dates.')->withInput();
            }

            $checkIn = new \Carbon\Carbon($request->check_in_date);
            $checkOut = new \Carbon\Carbon($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $totalAmount = $nights * $accommodation->price_per_night * $request->number_of_beds;

            // Reserve the beds
            if (!$accommodation->bookBeds($request->number_of_beds)) {
                throw new \Exception('Failed to reserve beds.');
            }

            $booking = Booking::create([
                'accommodation_id' => $request->accommodation_id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'number_of_beds' => $request->number_of_beds,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'special_requests' => $request->special_requests ?? '',
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'room_number' => $this->generateRoomNumber($accommodation),
                // Add new military fields
                'service_number' => $request->service_number,
                'rank' => $request->rank,
                'unit' => $request->unit,
                'branch' => $request->branch,
                'purpose' => $request->purpose,
            ]);

            DB::commit();

            return redirect()->route('home')->with('success', 'Booking submitted successfully! We will contact you within 24 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: '. $e->getMessage())->withInput();
        }
    }

    private function generateRoomNumber($accommodation)
    {
        // Generate room number based on block and available rooms
        $prefix = strtoupper(substr($accommodation->block_name, 0, 1));
        $existingRooms = Booking::where('accommodation_id', $accommodation->id)
            ->whereNotNull('room_number')
            ->pluck('room_number')
            ->toArray();

        for ($i = 1; $i <= $accommodation->total_beds; $i++) {
            $roomNumber = $prefix . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (!in_array($roomNumber, $existingRooms)) {
                return $roomNumber;
            }
        }

        return $prefix . 'XX'; // Fallback
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_beds' => 'required|integer|min:1',
        ]);

        $accommodation = Accommodation::findOrFail($request->accommodation_id);
        $isAvailable = $accommodation->isAvailableForBeds($request->number_of_beds, $request->check_in_date, $request->check_out_date);

        $checkIn = new \Carbon\Carbon($request->check_in_date);
        $checkOut = new \Carbon\Carbon($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $nights * $accommodation->price_per_night * $request->number_of_beds;

        return response()->json([
            'available' => $isAvailable,
            'total_amount' => $totalAmount,
            'total_formatted' => 'GHC'. number_format($totalAmount, 2),
            'nights' => $nights,
            'price_per_night' => $accommodation->price_per_night,
            'price_formatted' => 'GHC'. number_format($accommodation->price_per_night, 2),
            'available_beds' => $accommodation->available_beds,
        ]);
    }
}