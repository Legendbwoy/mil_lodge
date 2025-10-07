<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create()
    {
        $accommodations = Accommodation::where('is_available', true)->get();
        return view('reports.create', compact('accommodations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:amenity_issue,repair,renovation',
            'accommodation_id' => 'nullable|exists:accommodations,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports', 'public');
                $imagePaths[] = $path;
            }
        }

        // Create report
        $report = Report::create([
            'user_id' => Auth::id(),
            'accommodation_id' => $validated['accommodation_id'],
            'report_type' => $validated['report_type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'priority' => $validated['priority'],
            'images' => $imagePaths,
            'status' => 'pending'
        ]);

        return redirect()->route('reports.thankyou', $report->id)
                         ->with('success', 'Your report has been submitted successfully! We will address it soon.');
    }

    public function thankyou(Report $report)
    {
        return view('reports.thankyou', compact('report'));
    }

    public function index()
    {
        $reports = Report::where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        // Ensure user can only view their own reports
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }
}