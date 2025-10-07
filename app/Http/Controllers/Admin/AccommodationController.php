<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::with('amenities')->paginate(10);
        return view('admin.accommodations.index', compact('accommodations'));
    }

    public function create()
    {
        return view('admin.accommodations.create');
    }

    public function edit(Accommodation $accommodation)
    {
        return view('admin.accommodations.edit', compact('accommodation'));
    }

    /**
     * Store a newly created accommodation in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lodge_name' => 'required|string|max:255',
            'block_name' => 'required|string|max:255',
            'type' => 'required|string',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'description' => 'required|string',
            'total_beds' => 'required|integer|min:1|max:4',
            'available_beds' => 'required|integer|min:0|lte:total_beds',
            'max_guests' => 'required|integer|min:1|max:10',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|numeric|min:0.5',
            'square_feet' => 'nullable|numeric',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
            'family_friendly' => 'boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create a new accommodation instance
        $accommodation = new Accommodation();
        $accommodation->name = $validated['name'];
        $accommodation->lodge_name = $validated['lodge_name'];
        $accommodation->block_name = $validated['block_name'];
        $accommodation->type = $validated['type'];
        $accommodation->location = $validated['location'];
        $accommodation->price_per_night = $validated['price_per_night'];
        $accommodation->description = $validated['description'];
        $accommodation->total_beds = $validated['total_beds'];
        $accommodation->available_beds = $validated['available_beds'];
        $accommodation->max_guests = $validated['max_guests'];
        $accommodation->bedrooms = $validated['bedrooms'];
        $accommodation->bathrooms = $validated['bathrooms'];
        $accommodation->square_feet = $validated['square_feet'] ?? null;
        $accommodation->is_featured = $validated['is_featured'] ?? false;
        $accommodation->is_available = $validated['is_available'] ?? true;
        $accommodation->family_friendly = $validated['family_friendly'] ?? false;
        $accommodation->rating = 0;
        $accommodation->review_count = 0;

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('accommodations', 'public');
                $imagePaths[] = $path;
            }
            $accommodation->images = $imagePaths;
        }

        // Save the accommodation to the database
        $accommodation->save();

        // Handle amenities
        if (!empty($validated['amenities'])) {
            $amenityIds = [];
            foreach ($validated['amenities'] as $amenityName) {
                $amenity = Amenity::firstOrCreate(['name' => $amenityName]);
                $amenityIds[] = $amenity->id;
            }
            $accommodation->amenities()->sync($amenityIds);
        }

        // Redirect to the accommodations index with a success message
        return redirect()->route('admin.accommodations.index')->with('success',
            'Room created successfully with ' . $accommodation->total_beds . ' beds.');
    }

    /**
     * Update the specified accommodation in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lodge_name' => 'required|string|max:255',
            'block_name' => 'required|string|max:255',
            'type' => 'required|string',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'description' => 'required|string',
            'total_beds' => 'required|integer|min:1|max:4',
            'available_beds' => 'required|integer|min:0|lte:total_beds',
            'max_guests' => 'required|integer|min:1|max:10',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|numeric|min:0.5',
            'square_feet' => 'nullable|numeric',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
            'family_friendly' => 'boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update accommodation fields
        $accommodation->name = $validated['name'];
        $accommodation->lodge_name = $validated['lodge_name'];
        $accommodation->block_name = $validated['block_name'];
        $accommodation->type = $validated['type'];
        $accommodation->location = $validated['location'];
        $accommodation->price_per_night = $validated['price_per_night'];
        $accommodation->description = $validated['description'];
        $accommodation->total_beds = $validated['total_beds'];
        $accommodation->available_beds = $validated['available_beds'];
        $accommodation->max_guests = $validated['max_guests'];
        $accommodation->bedrooms = $validated['bedrooms'];
        $accommodation->bathrooms = $validated['bathrooms'];
        $accommodation->square_feet = $validated['square_feet'] ?? null;
        $accommodation->is_featured = $validated['is_featured'] ?? false;
        $accommodation->is_available = $validated['is_available'] ?? true;
        $accommodation->family_friendly = $validated['family_friendly'] ?? false;

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = $accommodation->images ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('accommodations', 'public');
                $imagePaths[] = $path;
            }
            $accommodation->images = $imagePaths;
        }

        // Save the accommodation to the database
        $accommodation->save();

        // Handle amenities
        if (!empty($validated['amenities'])) {
            $amenityIds = [];
            foreach ($validated['amenities'] as $amenityName) {
                $amenity = Amenity::firstOrCreate(['name' => $amenityName]);
                $amenityIds[] = $amenity->id;
            }
            $accommodation->amenities()->sync($amenityIds);
        } else {
            $accommodation->amenities()->detach();
        }

        // Redirect to the accommodations index with a success message
        return redirect()->route('admin.accommodations.index')->with('success',
            'Room updated successfully.');
    }
}