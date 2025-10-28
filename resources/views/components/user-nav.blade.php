<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar menu-light ">
    <div class="navbar-wrapper">
        <div class="navbar-content scroll-div">
            
            <!-- User Profile Section -->
            <div class="">
                <div class="main-menu-header">
                    <div class="user-details">
                        <div id="more-details">Welcome Guest <i class="fa fa-caret-down"></i></div>
                    </div>
                </div>
                <div class="collapse" id="nav-user-link">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="{{ route('home') }}" data-toggle="tooltip" title="Home">
                                <i class="feather icon-home"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('reports.create') }}" data-toggle="tooltip" title="Report Issue">
                                <i class="feather icon-alert-triangle"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="tel:+233XXXXXXXXX" data-toggle="tooltip" title="Call Support">
                                <i class="feather icon-phone"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Navigation -->
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>Main Navigation</label>
                </li>
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Home</span>
                    </a>
                </li>

                <!-- Reports & Support -->
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-alert-triangle"></i></span>
                        <span class="pcoded-mtext">Reports & Support</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li>
                            <a href="{{ route('reports.create') }}">
                                <i class="feather icon-plus mr-2"></i>Report New Issue
                            </a>
                        </li>
                        
                        <li>
                            <a href="tel:+233XXXXXXXXX">
                                <i class="feather icon-phone mr-2"></i>Call Support
                            </a>
                        </li>
                        <li>
                            <a href="mailto:support@akafia.com">
                                <i class="feather icon-mail mr-2"></i>Email Support
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Information -->
                <li class="nav-item pcoded-menu-caption">
                    <label>Information</label>
                </li>

                <li class="nav-item">
                    <a href="{{ route('contact') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-phone text-primary"></i></span>
                        <span class="pcoded-mtext">Contact</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('faq') }}" class="nav-link">
                        <span class="pcoded-micon"><i class="feather icon-help-circle text-success"></i></span>
                        <span class="pcoded-mtext">FAQ</span>
                    </a>
                </li>

            </ul>
            
            <!-- Support Card -->
            <div class="card text-center m-3">
                <div class="card-block">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="feather icon-headphones f-40 text-primary"></i>
                    <h6 class="mt-3">Need Help?</h6>
                    <p class="small">Our support team is here to assist you</p>
                    <div class="d-flex flex-column gap-2">
                        <a href="tel:+233XXXXXXXXX" class="btn btn-primary btn-sm text-white">
                            <i class="feather icon-phone mr-1"></i> Call Support
                        </a>
                        <a href="{{ route('reports.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="feather icon-message-square mr-1"></i> Report Issue
                        </a>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="feather icon-clock mr-1"></i> 24/7 Support Available
                        </small>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="feather icon-map-pin mr-1"></i> Oppong Peprah Lodge
                        </small>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->