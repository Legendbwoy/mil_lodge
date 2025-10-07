<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'accommodation' => 'App\Models\Accommodation',
            'booking' => 'App\Models\Booking',
        ]);
    }
}