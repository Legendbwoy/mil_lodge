<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccommodationController extends Controller
{
    public function index(Request $request)
    {
        $query = Accommodation::with('amenities')->available();

        if ($request->search) {
            $query->search($request->search);
        }

        if ($request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->guests) {
            $query->where('max_guests', '>=', (int) $request->guests);
        }

        $accommodations = $query->paginate(9);
        $amenities = Amenity::all();

        return view('welcome', compact('accommodations', 'amenities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|string',
            'location'        => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'description'     => 'required|string',
            'max_guests'      => 'required|integer|min:1',
            'bedrooms'        => 'required|integer|min:1',
            'bathrooms'       => 'required|numeric|min:0.5',
            'square_feet'     => 'nullable|integer',
            'images.*'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_featured']    = $request->has('is_featured');
        $validated['is_available']   = $request->has('is_available');
        $validated['family_friendly'] = $request->has('family_friendly');

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('accommodations', 'public');
                $imagePaths[] = "/storage/" . $path;
            }
        }

        $validated['images'] = $imagePaths;

        $accommodation = Accommodation::create($validated);

        return $accommodation
            ? redirect()->route('admin.accommodations.index')->with('success', 'Accommodation created successfully!')
            : back()->with('error', 'Failed to create accommodation.');
    }
    

    public function show($id)
    {
        $accommodation = Accommodation::with('amenities')->findOrFail($id);
        return view('accommodations.show', compact('accommodation'));
    }

    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'check_in'  => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'guests'    => 'required|integer|min:1'
        ]);

        $accommodation = Accommodation::findOrFail($id);

        $isAvailable = $accommodation->isAvailableForDates($request->check_in, $request->check_out);
        $totalPrice  = $accommodation->calculateTotalPrice($request->check_in, $request->check_out);

        return response()->json([
            'available'       => $isAvailable,
            'total_price'     => $totalPrice,
            'price_formatted' => 'GH¢' . number_format($totalPrice, 2),
            'nights'          => Carbon::parse($request->check_in)->diffInDays($request->check_out)
        ]);
    }

    /**
     * Availability check logic
     */
    private function checkAccommodationAvailability($accommodation, $checkIn, $checkOut)
    {
        // For now, always true — update with real booking logic later
        return true;

        // Example for future enhancement:
        /*
        return $accommodation->bookings()
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out_date', [$checkIn, $checkOut]);
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->doesntExist();
        */
    }

    /**
     * Calculate total stay price
     */
    private function calculateTotalPrice($accommodation, $checkIn, $checkOut)
    {
        $nights = Carbon::parse($checkIn)->diffInDays($checkOut);
        return $nights * $accommodation->price_per_night;
    }
}
