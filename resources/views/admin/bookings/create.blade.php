@extends('layouts.admin')

@section('title', 'Create Booking | Admin Dashboard')
@section('page-title', 'Create New Booking')

@section('content')
<!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Book <span id="modalAccommodationName"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}">
                    @csrf
                    <input type="hidden" name="accommodation_id" id="modalAccommodationId">
                    
                    <div class="modal-body">
                        <!-- Guest Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guest_name">Full Name *</label>
                                    <input type="text" class="form-control" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" required>
                                    @error('guest_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guest_email">Email Address *</label>
                                    <input type="email" class="form-control" id="guest_email" name="guest_email" value="{{ old('guest_email') }}" required>
                                    @error('guest_email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guest_phone">Phone Number *</label>
                                    <input type="tel" class="form-control" id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}" required>
                                    @error('guest_phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="number_of_guests">Number of Guests *</label>
                                    <select class="form-control" id="number_of_guests" name="number_of_guests" required>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('number_of_guests') == $i ? 'selected' : '' }}>
                                                {{ $i }} Guest{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('number_of_guests')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Booking Dates -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_in_date">Check-in Date *</label>
                                    <input type="date" class="form-control" id="check_in_date" name="check_in_date" value="{{ old('check_in_date') }}" required min="{{ date('Y-m-d') }}">
                                    @error('check_in_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="check_out_date">Check-out Date *</label>
                                    <input type="date" class="form-control" id="check_out_date" name="check_out_date" value="{{ old('check_out_date') }}" required>
                                    @error('check_out_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Purpose of Booking -->
                        <div class="form-group">
                            <label for="special_requests">Purpose of Booking</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Any special requirements or preferences?">{{ old('special_requests') }}</textarea>
                        </div>

                        <!-- Booking Summary -->
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Booking Summary</h6>
                                    <p class="mb-1" id="modalAccommodationInfo"></p>
                                    <p class="mb-1">Rate: <span id="modalPricePerNight"></span> per night</p>
                                    <p class="mb-0">Total: <span id="modalTotalPrice">$0.00</span> for <span id="modalNights">0</span> nights</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBookingBtn">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Booking Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accommodationSelect = document.getElementById('accommodation_id');
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const totalAmountInput = document.getElementById('total_amount');

    function calculateTotalAmount() {
        const accommodationId = accommodationSelect.value;
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);

        if (accommodationId && checkIn && checkOut && checkOut > checkIn) {
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            // In a real application, you would fetch the price via AJAX
            totalAmountInput.placeholder = `Calculating... (${nights} nights)`;
        }
    }

    accommodationSelect.addEventListener('change', calculateTotalAmount);
    checkInInput.addEventListener('change', calculateTotalAmount);
    checkOutInput.addEventListener('change', calculateTotalAmount);
});
</script>
@endsection