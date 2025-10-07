<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details #<span id="modalBookingId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Guest Information</h6>
                        <p><strong>Name:</strong> <span id="modalGuestName"></span></p>
                        <p><strong>Email:</strong> <span id="modalGuestEmail"></span></p>
                        <p><strong>Phone:</strong> <span id="modalGuestPhone"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2">Booking Information</h6>
                        <p><strong>Status:</strong> <span id="modalStatus" class="badge"></span></p>
                        <p><strong>Check-in:</strong> <span id="modalCheckIn"></span></p>
                        <p><strong>Check-out:</strong> <span id="modalCheckOut"></span></p>
                        <p><strong>Nights:</strong> <span id="modalNights"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Accommodation Details</h6>
                        <p><strong>Property:</strong> <span id="modalAccommodation"></span></p>
                        <p><strong>Type:</strong> <span id="modalAccommodationType"></span></p>
                        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Payment Details</h6>
                        <p><strong>Total Amount:</strong> GH¢<span id="modalTotalAmount"></span></p>
                        <p><strong>Payment Status:</strong> <span id="modalPaymentStatus" class="badge"></span></p>
                        <p><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                        <p><strong>Booked On:</strong> <span id="modalCreatedAt"></span></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2">Special Requests</h6>
                        <p id="modalSpecialRequests" class="text-muted">No special requests</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                
                {{-- Quick Status Dropdown in Modal --}}
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="feather icon-edit me-1"></i>Update Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Change Status</h6></li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="pending">
                                <span class="badge badge-warning me-1">●</span>Mark as Pending
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="confirmed">
                                <span class="badge badge-success me-1">●</span>Mark as Confirmed
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="checked_in">
                                <span class="badge badge-info me-1">●</span>Mark as Checked In
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item modal-quick-status-update" href="#" data-status="cancelled">
                                <span class="badge badge-danger me-1">●</span>Mark as Cancelled
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" id="modalFullEditLink">
                                <i class="feather icon-edit me-1"></i>Full Edit
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>