<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController        as AdminUserController;
use App\Http\Controllers\Admin\BookingController     as AdminBookingController;
use App\Http\Controllers\Admin\AccommodationController as AdminAccommodationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [AccommodationController::class, 'index'])->name('home');
Route::get('/accommodations/{accommodation}', [AccommodationController::class, 'show'])->name('accommodations.show');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

/*
|--------------------------------------------------------------------------
| Public Report Routes
|--------------------------------------------------------------------------
*/
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/create',   [ReportController::class, 'create'])->name('create');
    Route::post('/store',   [ReportController::class, 'store'])->name('store');
    Route::get('/thankyou/{report}', [ReportController::class, 'thankyou'])->name('thankyou');
    Route::get('/my-reports',        [ReportController::class, 'index'])->name('index');
    Route::get('/{report}',          [ReportController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Static Pages
|--------------------------------------------------------------------------
*/
Route::view('/about',   'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/faq',     'faq')->name('faq');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Notification routes
    Route::get('/notifications', [AdminController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/mark-read', [AdminController::class, 'markNotificationsAsRead'])->name('notifications.mark-read');

    // Users
    Route::resource('users', AdminUserController::class);

    // Bookings
    Route::resource('bookings', AdminBookingController::class);
    
    // Booking AJAX endpoints
    Route::get('/bookings/{id}/details', [AdminController::class, 'getBookingDetails'])->name('bookings.details');
    Route::post('/bookings/{id}/quick-status-update', [AdminController::class, 'updateBookingStatus'])->name('bookings.quick-status-update');

    // Accommodations
    Route::resource('accommodations', AdminAccommodationController::class);
    
    // Accommodation AJAX endpoints
    Route::post('/accommodations/{id}/update-status', [AdminController::class, 'updateAccommodationStatus'])->name('accommodations.update-status');
    Route::delete('accommodations/{accommodation}/remove-image/{imageIndex}',
        [AdminAccommodationController::class, 'removeImage'])
        ->name('accommodations.remove-image');

    // Reports Management
    Route::get('/reports', [AdminController::class, 'index'])->name('reports');
    
    // Reports AJAX endpoints
    Route::get('/reports/{id}/details', [AdminController::class, 'getReportDetails'])->name('reports.details');
    Route::post('/reports/{id}/update-status', [AdminController::class, 'updateReportStatus'])->name('reports.update-status');

    // Finance
    Route::get('/finance', [AdminController::class, 'finance'])->name('finance');
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect('/');
});