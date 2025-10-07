@extends('layouts.admin')

@section('title', 'Edit Booking | Admin Dashboard')
@section('page-title', 'Edit Booking')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Booking</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                            <li class="breadcrumb-item"><a href="#!">Edit</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Booking Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="accommodation_id">Accommodation *</label>
                                        <select class="form-control" id="accommodation_id" name="accommodation_id" required>
                                            <option value="">Select Accommodation</option>
                                            @foreach($accommodations as $accommodation)
                                                <option value="{{ $accommodation->id }}" 
                                                    {{ old('accommodation_id', $booking->accommodation_id) == $accommodation->id ? 'selected' : '' }}>
                                                    {{ $accommodation->name }} - ${{ number_format($accommodation->price_per_night, 2) }}/night
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Booking Status *</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="checked_in" {{ $booking->status == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                                            <option value="checked_out" {{ $booking->status == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_name">Guest Name *</label>
                                        <input type="text" class="form-control" id="guest_name" name="guest_name" required 
                                               value="{{ old('guest_name', $booking->guest_name) }}" placeholder="Enter guest name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_email">Guest Email *</label>
                                        <input type="email" class="form-control" id="guest_email" name="guest_email" required
                                               value="{{ old('guest_email', $booking->guest_email) }}" placeholder="Enter guest email">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guest_phone">Guest Phone *</label>
                                        <input type="tel" class="form-control" id="guest_phone" name="guest_phone" required
                                               value="{{ old('guest_phone', $booking->guest_phone) }}" placeholder="Enter guest phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="number_of_guests">Number of Guests *</label>
                                        <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" 
                                               required value="{{ old('number_of_guests', $booking->number_of_guests) }}" min="1">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_in_date">Check-in Date *</label>
                                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" required
                                               value="{{ old('check_in_date', $booking->check_in_date->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_out_date">Check-out Date *</label>
                                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" required
                                               value="{{ old('check_out_date', $booking->check_out_date->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="special_requests">Special Requests</label>
                                <textarea class="form-control" id="special_requests" name="special_requests" rows="3"
                                          placeholder="Any special requests or notes">{{ old('special_requests', $booking->special_requests) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="total_amount">Total Amount ($)</label>
                                <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount"
                                       value="{{ old('total_amount', $booking->total_amount) }}" placeholder="Will be calculated automatically">
                                <small class="form-text text-muted">Leave blank to auto-calculate based on dates and accommodation price</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather icon-save"></i> Update Booking
                                </button>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                                    <i class="feather icon-x"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection