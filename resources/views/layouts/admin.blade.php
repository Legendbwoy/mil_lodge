<!DOCTYPE html>
<html lang="en">

<head>
    <title>GAF ACCOMMODATION - ADMIN</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/GAF-Logo.png') }}" type="image/x-icon">
    
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <style>
        /* Your existing CSS styles remain the same */
        .logo-3d-container {
            width: 70px;
            height: 70px;
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
            animation: globe-rotate 15s linear infinite;
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
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .urgent-notification {
            border-left: 4px solid #ff4757;
            animation: blink 2s infinite;
        }
        
        .warning-notification {
            border-left: 4px solid #ffa502;
        }
        
        .info-notification {
            border-left: 4px solid #2ed573;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.7; }
        }
        
        .notification-time {
            font-size: 11px;
            color: #888;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .empty-notifications {
            text-align: center;
            padding: 20px;
            color: #6c757d;
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
        
        .logo-3d-depth {
            border-radius: 50%;
            box-shadow: 
                0 0 25px rgba(0, 100, 200, 0.3),
                inset 0 0 15px rgba(255, 255, 255, 0.2);
        }
        
        .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
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
    @include('components.admin-nav')
    <!-- [ Include Navigation ] end -->

    <!-- [ Header ] start -->
<header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        <a href="{{ route('admin.dashboard') }}" class="b-brand">
            <!-- 3D rotating GAF Logo with mirrored back -->
            <div class="logo-wrapper">
                <div class="logo-3d-container logo-globe-rotate">
                    <img src="{{ asset('assets/images/GAF-Logo.png') }}" alt="GAF Accommodation Front" 
                         class="logo-3d-front logo-3d-depth">
                    <img src="{{ asset('assets/images/GAF-Logo.png') }}" alt="GAF Accommodation Back" 
                         class="logo-3d-back logo-3d-depth">
                </div>
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
            <!-- Notifications Dropdown -->
            <li>
                <div class="dropdown">
                    <a class="dropdown-toggle position-relative" href="#" data-toggle="dropdown" id="notificationDropdown">
                        <i class="icon feather icon-bell"></i>
                        @if(isset($checkoutNotifications) && count($checkoutNotifications) > 0)
                            <span class="notification-badge" id="notificationCount">
                                {{ count($checkoutNotifications) }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right notification" style="width: 400px; max-height: 500px; overflow-y: auto;">
                        <div class="noti-head">
                            <h6 class="d-inline-block m-b-0">Check-out Notifications</h6>
                            <div class="float-right">
                                <a href="#!" class="m-r-10" id="markAllRead">mark as read</a>
                                <a href="#!" id="clearAll">clear all</a>
                            </div>
                        </div>
                        <ul class="noti-body" id="notificationList">
                            @if(isset($checkoutNotifications) && count($checkoutNotifications) > 0)
                                @foreach($checkoutNotifications as $notification)
                                    @php
                                        // Safely get notification values with defaults
                                        $icon = $notification['icon'] ?? 'bell';
                                        $color = $notification['color'] ?? 'primary';
                                        $type = $notification['type'] ?? 'info';
                                        $message = $notification['message'] ?? 'Notification';
                                        $accommodation = $notification['accommodation'] ?? 'Unknown Accommodation';
                                        $checkOutTime = $notification['check_out_time'] ?? 'Unknown';
                                        $timeRemaining = $notification['time_remaining'] ?? 'Unknown';
                                    @endphp
                                    <li class="notification notification-item {{ $type }}-notification">
                                        <div class="media">
                                            <div class="media-body">
                                                <p class="mb-1">
                                                    <strong>
                                                        <i class="feather icon-{{ $icon }} text-{{ $color }} mr-1"></i>
                                                        {{ $message }}
                                                    </strong>
                                                </p>
                                                <p class="mb-1 small">
                                                    <i class="feather icon-home mr-1"></i>{{ $accommodation }}
                                                </p>
                                                <p class="mb-0 small text-muted">
                                                    <i class="feather icon-clock mr-1"></i>
                                                    Check-out: {{ $checkOutTime }}
                                                    <span class="ml-2">•</span>
                                                    <span class="ml-2 text-{{ $color }}">
                                                        {{ $timeRemaining }} left
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="empty-notifications">
                                    <i class="feather icon-check-circle text-success" style="font-size: 48px;"></i>
                                    <p class="mt-2 mb-0">No upcoming check-outs</p>
                                    <small>You're all caught up!</small>
                                </li>
                            @endif
                        </ul>
                        <div class="noti-footer">
                            <a href="{{ route('admin.bookings.index') }}">View All Bookings</a>
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
                            <a href="#!" class="dud-logout" title="Logout">
                                <i class="feather icon-log-out"></i>
                            </a>
                        </div>
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

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ripple.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
    <script src="{{ asset('assets/js/menu-setting.min.js') }}"></script>
    
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/todo.js') }}"></script>

    <script>
        // Real-time notification system
        $(document).ready(function() {
            let notificationCheckInterval;
            
            // Function to update notifications
            function updateNotifications() {
                $.ajax({
                    url: '/admin/notifications',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            updateNotificationUI(response.notifications, response.unread_count);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch notifications:', error);
                    }
                });
            }
            
            // In your JavaScript, update the updateNotificationUI function:
function updateNotificationUI(notifications, unreadCount) {
    const notificationList = $('#notificationList');
    const notificationCount = $('#notificationCount');
    
    // Update notification count
    if (unreadCount > 0) {
        if (notificationCount.length === 0) {
            $('#notificationDropdown').append('<span class="notification-badge" id="notificationCount">' + unreadCount + '</span>');
        } else {
            notificationCount.text(unreadCount);
        }
    } else {
        notificationCount.remove();
    }
    
    // Update notification list
    if (notifications.length > 0) {
        let notificationsHTML = '';
        notifications.forEach(notification => {
            // Safely get all notification properties with defaults
            const icon = notification.icon || 'bell';
            const color = notification.color || 'primary';
            const type = notification.type || 'info';
            const message = notification.message || 'Check-out reminder';
            const accommodation = notification.accommodation || 'Unknown Accommodation';
            const checkOutTime = notification.check_out_time || 'Unknown time';
            const timeRemaining = notification.time_remaining || 'Unknown';
            const bookingId = notification.booking_id || 'Unknown';
            const guestName = notification.guest_name || 'Unknown Guest';
            
            notificationsHTML += `
                <li class="notification notification-item ${type}-notification">
                    <div class="media">
                        <div class="media-body">
                            <p class="mb-1">
                                <strong>
                                    <i class="feather icon-${icon} text-${color} mr-1"></i>
                                    ${message}
                                </strong>
                            </p>
                            <p class="mb-1 small">
                                <i class="feather icon-home mr-1"></i>${accommodation}
                            </p>
                            <p class="mb-0 small text-muted">
                                <i class="feather icon-clock mr-1"></i>
                                Check-out: ${checkOutTime}
                                <span class="ml-2">•</span>
                                <span class="ml-2 text-${color}">
                                    ${timeRemaining} left
                                </span>
                            </p>
                            <p class="mb-0 small text-muted">
                                Guest: ${guestName} • Booking #${bookingId}
                            </p>
                        </div>
                    </div>
                </li>
            `;
        });
        notificationList.html(notificationsHTML);
    } else {
        notificationList.html(`
            <li class="empty-notifications">
                <i class="feather icon-check-circle text-success" style="font-size: 48px;"></i>
                <p class="mt-2 mb-0">No upcoming check-outs</p>
                <small>You're all caught up!</small>
            </li>
        `);
    }
    
    // Show browser notification for urgent alerts
    const urgentNotifications = notifications.filter(n => n.type === 'urgent');
    if (urgentNotifications.length > 0 && Notification.permission === 'granted') {
        urgentNotifications.forEach(notification => {
            new Notification('Urgent Check-out Alert', {
                body: notification.message || 'Guest needs to check out soon',
                icon: '/assets/images/GAF-Logo.png'
            });
        });
    }
}
            
            // Mark all as read
            $('#markAllRead').on('click', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: '/admin/notifications/mark-read',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#notificationCount').remove();
                            showToast('Success', 'Notifications marked as read', 'success');
                        }
                    }
                });
            });
            
            // Clear all notifications
            $('#clearAll').on('click', function(e) {
                e.preventDefault();
                $('#notificationList').html(`
                    <li class="empty-notifications">
                        <i class="feather icon-check-circle text-success" style="font-size: 48px;"></i>
                        <p class="mt-2 mb-0">No upcoming check-outs</p>
                        <small>You're all caught up!</small>
                    </li>
                `);
                $('#notificationCount').remove();
            });
            
            // Request notification permission
            if ('Notification' in window) {
                Notification.requestPermission();
            }
            
            // Start checking for notifications every 30 seconds
            notificationCheckInterval = setInterval(updateNotifications, 30000);
            
            // Also check when the notification dropdown is opened
            $('#notificationDropdown').on('click', function() {
                updateNotifications();
            });
            
            // Initial notification check
            updateNotifications();
            
            // Toast notification function
            function showToast(title, message, type = 'info') {
                // Create toast container if it doesn't exist
                let toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '9999';
                    document.body.appendChild(toastContainer);
                }

                const toastId = 'toast-' + Date.now();
                const bgColor = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
                
                const toastHTML = `
                    <div id="${toastId}" class="toast align-items-center text-white ${bgColor} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <strong>${title}:</strong> ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                
                toastContainer.insertAdjacentHTML('beforeend', toastHTML);
                
                const toastElement = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 3000
                });
                toast.show();
                
                // Remove toast from DOM after hide
                toastElement.addEventListener('hidden.bs.toast', () => {
                    toastElement.remove();
                });
            }
            
            // Pause rotation on hover
            $('.logo-3d-container').hover(
                function() {
                    $(this).css('animation-play-state', 'paused');
                },
                function() {
                    $(this).css('animation-play-state', 'running');
                }
            );
            
            $('.loader-3d-container').hover(
                function() {
                    $(this).css('animation-play-state', 'paused');
                },
                function() {
                    $(this).css('animation-play-state', 'running');
                }
            );
        });
    </script>
</body>
</html>