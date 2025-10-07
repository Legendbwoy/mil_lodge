<!DOCTYPE html>
<html lang="en">

<head>
    <title>Akafia | Opong Peprah Lodge</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom Styles -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        :root {
            --primary-color: #4680ff;
            --secondary-color: #6c757d;
            --success-color: #4CAF50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --info-color: #00bcd4;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            background-color: #f5f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .accommodation-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .accommodation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-img-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .card-img-top {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .accommodation-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.7));
            display: flex;
            align-items: flex-end;
            padding: 15px;
            color: white;
        }

        .price-tag {
            background: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .amenities-list {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }

        .amenities-list li {
            display: inline-block;
            background: #f0f5ff;
            color: var(--primary-color);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .rating {
            color: #ffc107;
        }

        .modal-content {
            border-radius: 10px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            background: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #3a6bd6;
            border-color: #3a6bd6;
        }

        .search-filter {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            border-left: 4px solid var(--primary-color);
            padding-left: 15px;
            margin-bottom: 25px;
        }

        .featured-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--danger-color);
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            z-index: 2;
        }

        .view-details-btn {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .view-details-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .gallery-thumbnails {
            display: flex;
            margin-top: 15px;
            overflow-x: auto;
        }

        .gallery-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .gallery-thumb:hover,
        .gallery-thumb.active {
            opacity: 1;
        }

         .pcoded-navbar {
            transition: width 0.3s ease;
        }
        .pcoded-navbar.collapsed {
            width: 60px; /* Collapsed width */
        }
        .pcoded-navbar .pcoded-inner-navbar {
            display: block; /* Show items */
        }
        .pcoded-navbar.collapsed .pcoded-inner-navbar {
            display: none; /* Hide items when collapsed */
        }
        .btn-toggle {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }

            .logo {
        width: 100px; /* Adjust the width as needed */
        height: auto; /* Maintain aspect ratio */
    }

    .spinning-logo {
        animation: spin 5s linear infinite; /* Adjust duration for speed */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    </style>
</head>

<body>
<!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Include Navigation ] start -->
    @include('layouts.sidenav')
    <!-- [ Include Navigation ] end -->
</body>

    <!-- [ Header ] start -->
<header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        <a href="#!" class="b-brand">
            <img src="{{ asset('assets/images/GAF-Logo.png') }}" alt="" class="logo spinning-logo">
        </a>
    </div>
</header>
<!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">@yield('page-title')</h5>
                            </div>
                            <ul class="breadcrumb">
                                {{-- <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">@yield('breadcrumb-title')</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] -->
            @yield('content')
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

<!-- Footer -->
{{-- <footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Akafia Lodge</h5>
                <p>Find your perfect accommodation with our premium selection of properties worldwide.</p>
            </div>
            <div class="col-md-2">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light">Home</a></li>
                    <li><a href="#" class="text-light">Accommodations</a></li>
                    <li><a href="#" class="text-light">About Us</a></li>
                    <li><a href="#" class="text-light">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Contact Us</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-envelope mr-2"></i> info@luxstay.com</li>
                    <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Main St, New York, NY</li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Follow Us</h5>
                <div class="social-links">
                    <a href="#" class="text-light mr-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light mr-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light mr-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-light">
        <div class="text-center">
            <p class="mb-0">&copy; 2023 LuxStay. All rights reserved.</p>
        </div>
    </div>
</footer> --}}

<!-- jQuery and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Gallery thumbnail click handler
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Remove active class from all thumbs
            document.querySelectorAll('.gallery-thumb').forEach(t => {
                t.classList.remove('active');
            });
            // Add active class to clicked thumb
            this.classList.add('active');
        });
    });

    // Calculate and update booking total
    function updateBookingTotal() {
        // This would calculate based on selected dates in a real application
        // For demo purposes, we'll just show a static calculation
    }

    // Initialize date pickers with min date as today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(input => {
            input.min = today;
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const logo = document.querySelector('.spinning-logo');
        logo.classList.add('spinning-logo');
    });
</script>
</body>

</html>
