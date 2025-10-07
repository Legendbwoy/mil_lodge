<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar menu-light">
    <div class="navbar-wrapper">
        <div class="navbar-content scroll-div">
            
            <!-- Admin Profile Section -->
            <div class="">
                <div class="main-menu-header">
                    <img class="img-radius" src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="Admin-Profile-Image">
                    <div class="user-details">
                        <div id="more-details">Administrator <i class="fa fa-caret-down"></i></div>
                    </div>
                </div>
                <div class="collapse" id="nav-user-link">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="{{ route('admin.dashboard') }}" data-toggle="tooltip" title="Dashboard">
                                <i class="feather icon-home"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('reports.index') }}" data-toggle="tooltip" title="Reports">
                                <i class="feather icon-alert-triangle"></i>
                                @php
                                    $pendingReports = \App\Models\Report::where('status', 'pending')->count();
                                @endphp
                                @if($pendingReports > 0)
                                    <small class="badge badge-pill badge-danger">{{ $pendingReports }}</small>
                                @endif
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('home') }}" data-toggle="tooltip" title="Logout" class="text-danger">
                                <i class="feather icon-power"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Navigation -->
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigation</label>
                </li>
                
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>

                <!-- Quick Actions -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Quick Actions</label>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.bookings.create') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-plus-circle text-success"></i></span>
                        <span class="pcoded-mtext">Create Booking</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.accommodations.create') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-home text-primary"></i></span>
                        <span class="pcoded-mtext">Add Accommodation</span>
                    </a>
                </li>
                
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.users.create') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-user-plus text-info"></i></span>
                        <span class="pcoded-mtext">Add User</span>
                    </a>
                </li> --}}

                <!-- Management Sections -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Management</label>
                </li>

                <!-- Bookings Management -->
                <li class="nav-item pcoded-hasmenu {{ request()->routeIs('admin.bookings.*') ? 'active pcoded-trigger' : '' }}">
                    <a href="#!" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-calendar"></i></span>
                        <span class="pcoded-mtext">Bookings</span>
                        @php
                            $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
                        @endphp
                        @if($pendingBookings > 0)
                            <span class="pcoded-badge badge badge-danger">{{ $pendingBookings }}</span>
                        @endif
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ request()->routeIs('admin.bookings.index') && !request()->has('status') ? 'active' : '' }}">
                            <a href="{{ route('admin.bookings.index') }}">
                                <i class="feather icon-list mr-2"></i>All Bookings
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'pending' ? 'active' : '' }}">
                            <a href="{{ route('admin.bookings.index') }}?status=pending">
                                <i class="feather icon-clock mr-2"></i>Pending Bookings
                                @if($pendingBookings > 0)
                                    <span class="badge badge-warning float-right">{{ $pendingBookings }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'confirmed' ? 'active' : '' }}">
                            <a href="{{ route('admin.bookings.index') }}?status=confirmed">
                                <i class="feather icon-check-circle mr-2"></i>Confirmed
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'checked_in' ? 'active' : '' }}">
                            <a href="{{ route('admin.bookings.index') }}?status=checked_in">
                                <i class="feather icon-log-in mr-2"></i>Checked In
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.bookings.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.bookings.create') }}">
                                <i class="feather icon-plus mr-2"></i>Create New
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Accommodations Management -->
                <li class="nav-item pcoded-hasmenu {{ request()->routeIs('admin.accommodations.*') ? 'active pcoded-trigger' : '' }}">
                    <a href="#!" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Accommodations</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ request()->routeIs('admin.accommodations.index') && !request()->has('status') ? 'active' : '' }}">
                            <a href="{{ route('admin.accommodations.index') }}">
                                <i class="feather icon-list mr-2"></i>All Properties
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'available' ? 'active' : '' }}">
                            <a href="{{ route('admin.accommodations.index') }}?status=available">
                                <i class="feather icon-check-circle mr-2"></i>Available
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'occupied' ? 'active' : '' }}">
                            <a href="{{ route('admin.accommodations.index') }}?status=occupied">
                                <i class="feather icon-user mr-2"></i>Occupied
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'maintenance' ? 'active' : '' }}">
                            <a href="{{ route('admin.accommodations.index') }}?status=maintenance">
                                <i class="feather icon-tool mr-2"></i>Maintenance
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.accommodations.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.accommodations.create') }}">
                                <i class="feather icon-plus mr-2"></i>Add New
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Reports Management -->
                <li class="nav-item pcoded-hasmenu {{ request()->routeIs('reports.index') ? 'active pcoded-trigger' : '' }}">
                    <a href="#!" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-alert-triangle"></i></span>
                        <span class="pcoded-mtext">Reports & Issues</span>
                        @if($pendingReports > 0)
                            <span class="pcoded-badge badge badge-danger">{{ $pendingReports }}</span>
                        @endif
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ request()->routeIs('admin.reports') && !request()->has('status') ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}">
                                <i class="feather icon-list mr-2"></i>All Reports
                                @if($pendingReports > 0)
                                    <span class="badge badge-danger float-right">{{ $pendingReports }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'pending' ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}?status=pending">
                                <i class="feather icon-clock mr-2"></i>Pending Reports
                                @if($pendingReports > 0)
                                    <span class="badge badge-warning float-right">{{ $pendingReports }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'in_progress' ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}?status=in_progress">
                                <i class="feather icon-settings mr-2"></i>In Progress
                            </a>
                        </li>
                        <li class="{{ request()->get('status') == 'resolved' ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}?status=resolved">
                                <i class="feather icon-check-circle mr-2"></i>Resolved
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Users Management -->
                {{-- <li class="nav-item pcoded-hasmenu {{ request()->routeIs('admin.users.*') ? 'active pcoded-trigger' : '' }}">
                    <a href="#!" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Users</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}">
                                <i class="feather icon-list mr-2"></i>All Users
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.create') }}">
                                <i class="feather icon-user-plus mr-2"></i>Add New User
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <!-- Finance & Analytics -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Analytics & Finance</label>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.finance') ? 'active' : '' }}">
                    <a href="{{ route('admin.finance') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-dollar-sign"></i></span>
                        <span class="pcoded-mtext">Finance Overview</span>
                    </a>
                </li>

                <!-- Notifications -->
                {{-- <li class="nav-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                    <a href="{{ route('admin.notifications') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-bell"></i></span>
                        <span class="pcoded-mtext">Notifications</span>
                        @php
                            $unreadNotifications = auth()->user()->unreadNotifications->count();
                        @endphp
                        @if($unreadNotifications > 0)
                            <span class="pcoded-badge badge badge-danger">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                </li> --}}

                <!-- System -->
                <li class="nav-item pcoded-menu-caption">
                    <label>System</label>
                </li>

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link" target="_blank">
                        <span class="pcoded-micon"><i class="feather icon-external-link"></i></span>
                        <span class="pcoded-mtext">View Public Site</span>
                    </a>
                </li>

            </ul>
            
            <!-- Support Card -->
            <div class="card text-center mt-3">
                <div class="card-block">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="feather icon-settings f-40 text-primary"></i>
                    <h6 class="mt-3">Admin Support</h6>
                    <p class="small mb-2">Technical assistance & system help</p>
                    <div class="d-flex flex-column gap-2">
                        <a href="tel:+233XXXXXXXXX" class="btn btn-primary btn-sm text-white">
                            <i class="feather icon-phone mr-1"></i> Emergency
                        </a>
                        <a href="mailto:admin@akafia.com" class="btn btn-outline-primary btn-sm">
                            <i class="feather icon-mail mr-1"></i> Email Support
                        </a>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="feather icon-shield mr-1"></i> Admin Access Only
                        </small>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->