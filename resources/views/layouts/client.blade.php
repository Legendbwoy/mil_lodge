<!DOCTYPE html>
<html lang="en">

<head>
    <title>GAF ACCOMMODATION - USER</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('assets/images/GAF-Logo.png') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ekko-lightbox css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/ekko-lightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/lightbox.min.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* 3D Globe Rotation with Mirrored Back for User Layout */
        .logo-3d-container {
            width: 40px;
            height: 40px;
            position: relative;
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        
        .logo-3d-front, .logo-3d-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: visible;
            -webkit-backface-visibility: visible;
        }
        
        .logo-3d-front {
            transform: rotateY(0deg);
        }
        
        .logo-3d-back {
            transform: rotateY(180deg);
            filter: brightness(0.8) contrast(1.2);
        }
        
        .logo-globe-rotate {
            animation: globe-rotate 12s linear infinite;
            transform-style: preserve-3d;
        }
        
        @keyframes globe-rotate {
            0% {
                transform: rotateY(0deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }
        

        
        .loader-logo-front, .loader-logo-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: visible;
        }
        
        .loader-logo-front {
            transform: rotateY(0deg);
        }
        
        .loader-logo-back {
            transform: rotateY(180deg);
            filter: brightness(0.8) contrast(1.2);
        }
        
        .loader-globe-rotate {
            animation: globe-rotate 10s linear infinite;
            transform-style: preserve-3d;
        }
        
        /* Enhanced 3D effects */
        .logo-3d-depth {
            border-radius: 50%;
            box-shadow: 
                0 0 25px rgba(0, 100, 200, 0.3),
                inset 0 0 15px rgba(255, 255, 255, 0.2);
        }
        
        /* Container styling */
        .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Custom styles for better spacing and layout */
        .main-content-wrapper {
            min-height: calc(100vh - 200px);
            padding: 20px 0;
        }
        
        .page-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .section-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .accommodation-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            height: 100%;
        }
        
        .accommodation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        
        .card-img-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        
        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .accommodation-card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .featured-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }
        
        .price-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            z-index: 2;
        }
        
        .img-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
            padding: 20px 15px 10px;
            z-index: 1;
        }
        
        .amenities-list {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .amenities-list li {
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .search-filter {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .search-filter .form-group {
            margin-bottom: 0;
        }
        
        .search-filter label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .pagination {
            margin: 30px 0 10px;
        }
        
        .page-item.active .page-link {
            background-color: #4680ff;
            border-color: #4680ff;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content-wrapper {
                padding: 10px 0;
            }
            
            .page-content {
                padding: 0 10px;
            }
            
            .search-filter {
                padding: 15px;
            }
            
            .search-filter .row > div {
                margin-bottom: 15px;
            }
            
            .card-img-container {
                height: 180px;
            }
        }
        
        /* Alert styling */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 25px;
        }
        
        /* Button styling */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 16px;
        }
        
        .view-details-btn, .book-now-btn {
            min-width: 110px;
        }
    </style>
</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
        <!-- 3D rotating logo with mirrored back in pre-loader -->
        <div class="loader-3d-container loader-globe-rotate">
            <img src="{{ asset('assets/images/GAF-Logo.png') }}" alt="" 
                 class="loader-logo-front logo-3d-depth">
            <img src="{{ asset('assets/images/GAF-Logo.png') }}" alt="" 
                 class="loader-logo-back logo-3d-depth">
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Include Navigation ] start -->
    @include('components.user-nav')
    <!-- [ Include Navigation ] end -->

    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            <a href="#!" class="b-brand">
                <!-- 3D rotating GAF Logo with mirrored back -->
                <div class="logo-wrapper">
                    <div class="logo-3d-container logo-globe-rotate">
                        <img src="{{ asset('assets/images/GAF-Logo.png') }}" alt="GAF Accommodation Front" 
                             class="logo-3d-front logo-3d-depth">
                        <img src="{{ asset('assets/images/GAF-Logo.png') }}" alt="GAF Accommodation Back" 
                             class="logo-3d-back logo-3d-depth">
                    </div>
                    <span class="ml-2 text-white font-weight-bold">GAF ACCOMMODATION</span>
                </div>
            </a>
            <a href="#!" class="mob-toggler">
                <i class="feather icon-more-vertical"></i>
            </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
                    <div class="search-bar">
                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search here">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li>
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                        <div class="dropdown-menu dropdown-menu-right notification">
                            <div class="noti-head">
                                <h6 class="d-inline-block m-b-0">Notifications</h6>
                                <div class="float-right">
                                    <a href="#!" class="m-r-10">mark as read</a>
                                    <a href="#!">clear all</a>
                                </div>
                            </div>
                            <ul class="noti-body">
                                <li class="n-title">
                                    <p class="m-b-0">NEW</p>
                                </li>
                                <li class="notification">
                                    <div class="media">
                                        <img class="img-radius" src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <p><strong>John Doe</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>5 min</span></p>
                                            <p>New ticket Added</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="n-title">
                                    <p class="m-b-0">EARLIER</p>
                                </li>
                                <li class="notification">
                                    <div class="media">
                                        <img class="img-radius" src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <p><strong>Joseph William</strong><span class="n-time text-muted"><i class="icon feather icon-clock m-r-10"></i>10 min</span></p>
                                            <p>Purchase New Theme and make payment</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="noti-footer">
                                <a href="#!">show all</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="feather icon-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" class="img-radius" alt="User-Profile-Image">
                                <span>John Doe</span>
                                <a href="auth-signin.html" class="dud-logout" title="Logout">
                                    <i class="feather icon-log-out"></i>
                                </a>
                            </div>
                            <ul class="pro-body">
                                <li><a href="user-profile.html" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                                <li><a href="email_inbox.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li>
                                <li><a href="auth-signin.html" class="dropdown-item"><i class="feather icon-lock"></i> Lock Screen</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
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
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">@yield('breadcrumb-title', 'Home')</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] -->
            <div class="main-content-wrapper">
                <div class="page-content">
                    @yield('content')
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Warning Section start -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
               <br/>to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="assets/images/browser/chrome.png" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="assets/images/browser/firefox.png" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="assets/images/browser/opera.png" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="assets/images/browser/safari.png" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="assets/images/browser/ie.png" alt="">
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
    <![endif]-->
    <!-- Warning Section Ends -->

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ripple.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('assets/js/menu-setting.min.js') }}"></script>

    <!-- ekko-lightbox Js -->
    <script src="{{ asset('assets/js/plugins/ekko-lightbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/lightbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/ac-lightbox.js') }}"></script>

    <!-- Custom Scripts -->
    @yield('scripts')

    <script>
        
        // [ customer-scroll ] start
        var px = new PerfectScrollbar('.cust-scroll', {
            wheelSpeed: .5,
            swipeEasing: 0,
            wheelPropagation: 1,
            minScrollbarLength: 40,
        });
        // [ customer-scroll ] end

        document.addEventListener("DOMContentLoaded", function() {
            // Pause rotation on hover for user experience
            const logoContainers = document.querySelectorAll('.logo-3d-container, .loader-3d-container');
            
            logoContainers.forEach(container => {
                container.addEventListener('mouseenter', function() {
                    this.style.animationPlayState = 'paused';
                });
                
                container.addEventListener('mouseleave', function() {
                    this.style.animationPlayState = 'running';
                });
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>