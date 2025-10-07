@extends('layouts.app')

@section('title', 'Akafia | Opong Peprah Lodge')
@section('page-title', 'Available Accommodations')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="container mt-4">
        <!-- Card for Header and Search Section -->
        <div class="card">
            <div class="card-body">
                <!-- Page Header -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h2 class="section-title">Available Accommodations</h2>
                        <p class="text-muted">Discover your perfect stay from our curated selection of premium accommodations.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary active">
                                <i class="fas fa-th"></i> Grid
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> List
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="search-filter mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Destination</label>
                                <input type="text" class="form-control" placeholder="Where are you going?">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Check-in</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Check-out</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Guests</label>
                                <select class="form-control">
                                    <option>1 Guest</option>
                                    <option>2 Guests</option>
                                    <option>3 Guests</option>
                                    <option>4+ Guests</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-2"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accommodation Cards -->
                <div class="row">
                    <!-- Accommodation 1 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card accommodation-card">
                            <div class="card-img-container">
                                <span class="featured-badge">Featured</span>
                                <span class="price-tag">GH¢120/night</span>
                                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
                                     class="card-img-top" alt="Luxury Apartment">
                                <div class="img-overlay">
                                    <div>
                                        <h5 class="mb-0">Luxury Apartment</h5>
                                        <small><i class="fas fa-map-marker-alt"></i> Downtown, New York</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span class="text-muted ml-1">(4.5)</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-bed mr-1 text-primary"></i> 2 Beds
                                        <i class="fas fa-bath ml-2 mr-1 text-primary"></i> 2 Baths
                                    </div>
                                </div>
                                <p class="card-text">Spacious luxury apartment in the heart of downtown with stunning city views and premium amenities.</p>
                                <ul class="amenities-list">
                                    <li>WiFi</li>
                                    <li>Pool</li>
                                    <li>Gym</li>
                                    <li>Parking</li>
                                </ul>
                                <div class="d-flex justify-content-between mt-3">
                                    <button class="btn btn-outline-primary view-details-btn" data-toggle="modal"
                                            data-target="#detailsModal1">
                                        <i class="fas fa-info-circle mr-1"></i> Details
                                    </button>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookingModal1">
                                        <i class="fas fa-calendar-check mr-1"></i> Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accommodation 2 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card accommodation-card">
                            <div class="card-img-container">
                                <span class="price-tag">GH¢95/night</span>
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
                                     class="card-img-top" alt="Beach Villa">
                                <div class="img-overlay">
                                    <div>
                                        <h5 class="mb-0">Beachfront Villa</h5>
                                        <small><i class="fas fa-map-marker-alt"></i> Malibu, California</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <span class="text-muted ml-1">(4.0)</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-bed mr-1 text-primary"></i> 3 Beds
                                        <i class="fas fa-bath ml-2 mr-1 text-primary"></i> 2 Baths
                                    </div>
                                </div>
                                <p class="card-text">Beautiful beachfront villa with direct access to private beach and panoramic ocean views.</p>
                                <ul class="amenities-list">
                                    <li>Beach Access</li>
                                    <li>Hot Tub</li>
                                    <li>BBQ</li>
                                    <li>WiFi</li>
                                </ul>
                                <div class="d-flex justify-content-between mt-3">
                                    <button class="btn btn-outline-primary view-details-btn" data-toggle="modal"
                                            data-target="#detailsModal2">
                                        <i class="fas fa-info-circle mr-1"></i> Details
                                    </button>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookingModal2">
                                        <i class="fas fa-calendar-check mr-1"></i> Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accommodation 3 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card accommodation-card">
                            <div class="card-img-container">
                                <span class="price-tag">GH¢75/night</span>
                                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
                                     class="card-img-top" alt="Mountain Cabin">
                                <div class="img-overlay">
                                    <div>
                                        <h5 class="mb-0">Mountain Cabin</h5>
                                        <small><i class="fas fa-map-marker-alt"></i> Aspen, Colorado</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="text-muted ml-1">(5.0)</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-bed mr-1 text-primary"></i> 2 Beds
                                        <i class="fas fa-bath ml-2 mr-1 text-primary"></i> 1 Bath
                                    </div>
                                </div>
                                <p class="card-text">Cozy mountain cabin with fireplace, perfect for a romantic getaway or small family vacation.</p>
                                <ul class="amenities-list">
                                    <li>Fireplace</li>
                                    <li>Hiking</li>
                                    <li>WiFi</li>
                                    <li>Pet Friendly</li>
                                </ul>
                                <div class="d-flex justify-content-between mt-3">
                                    <button class="btn btn-outline-primary view-details-btn" data-toggle="modal"
                                            data-target="#detailsModal3">
                                        <i class="fas fa-info-circle mr-1"></i> Details
                                    </button>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookingModal3">
                                        <i class="fas fa-calendar-check mr-1"></i> Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Modals -->
    <!-- Booking Modal for Luxury Apartment -->
    <div class="modal fade" id="bookingModal1" tabindex="-1" role="dialog" aria-labelledby="bookingModal1Label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModal1Label">Book Luxury Apartment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="checkin1">Check-in Date</label>
                            <input type="date" class="form-control" id="checkin1" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout1">Check-out Date</label>
                            <input type="date" class="form-control" id="checkout1" required>
                        </div>
                        <div class="form-group">
                            <label for="guests1">Number of Guests</label>
                            <select class="form-control" id="guests1">
                                <option>1 Guest</option>
                                <option>2 Guests</option>
                                <option>3 Guests</option>
                                <option>4 Guests</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="specialRequests1">Purpose of Booking</label>
                            <textarea class="form-control" id="specialRequests1" rows="3" placeholder="Any special requirements?"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <h6>Booking Summary</h6>
                            <p class="mb-1">Luxury Apartment - Downtown, New York</p>
                            <p class="mb-1">Rate: $120 per night</p>
                            <p class="mb-0">Total for 3 nights: $360</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Booking Modal for Beachfront Villa -->
    <div class="modal fade" id="bookingModal2" tabindex="-1" role="dialog" aria-labelledby="bookingModal2Label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModal2Label">Book Beachfront Villa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="checkin2">Check-in Date</label>
                            <input type="date" class="form-control" id="checkin2" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout2">Check-out Date</label>
                            <input type="date" class="form-control" id="checkout2" required>
                        </div>
                        <div class="form-group">
                            <label for="guests2">Number of Guests</label>
                            <select class="form-control" id="guests2">
                                <option>1 Guest</option>
                                <option>2 Guests</option>
                                <option>3 Guests</option>
                                <option>4 Guests</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="specialRequests2">Purpose of Booking</label>
                            <textarea class="form-control" id="specialRequests2" rows="3" placeholder="What's your purpose of booking?"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <h6>Booking Summary</h6>
                            <p class="mb-1">Beachfront Villa - Malibu, California</p>
                            <p class="mb-1">Rate: $95 per night</p>
                            <p class="mb-0">Total for 3 nights: $285</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Booking Modal for Mountain Cabin -->
    <div class="modal fade" id="bookingModal3" tabindex="-1" role="dialog" aria-labelledby="bookingModal3Label"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModal3Label">Book Mountain Cabin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="checkin3">Check-in Date</label>
                            <input type="date" class="form-control" id="checkin3" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout3">Check-out Date</label>
                            <input type="date" class="form-control" id="checkout3" required>
                        </div>
                        <div class="form-group">
                            <label for="guests3">Number of Guests</label>
                            <select class="form-control" id="guests3">
                                <option>1 Guest</option>
                                <option>2 Guests</option>
                                <option>3 Guests</option>
                                <option>4 Guests</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="specialRequests3">Purpose of Booking</label>
                            <textarea class="form-control" id="specialRequests3" rows="3" placeholder="Any special requirements?"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <h6>Booking Summary</h6>
                            <p class="mb-1">Mountain Cabin - Aspen, Colorado</p>
                            <p class="mb-1">Rate: $75 per night</p>
                            <p class="mb-0">Total for 3 nights: $225</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Optional: Add any additional JavaScript here if needed
    </script>
@endsection