<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Accommodation; // Add this import
use Illuminate\Http\Request;

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

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }
}