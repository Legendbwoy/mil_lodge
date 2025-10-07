<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login')
                ->with('error', 'You must be an admin to access this area.');
        }

        return $next($request);
    }
}
