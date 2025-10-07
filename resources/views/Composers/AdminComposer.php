<?php

namespace App\Http\View\Composers;

use App\Models\Booking;
use App\Models\Report;
use Illuminate\View\View;

class AdminComposer
{
    public function compose(View $view)
    {
        $pendingBookingsCount = Booking::where('status', 'pending')->count();
        $pendingReportsCount = Report::where('status', 'pending')->count();
        
        $view->with([
            'pendingBookingsCount' => $pendingBookingsCount,
            'pendingReportsCount' => $pendingReportsCount,
        ]);
    }
}