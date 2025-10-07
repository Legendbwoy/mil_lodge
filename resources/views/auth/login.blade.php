<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Login | Ablepro Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Admin login panel" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
<!-- [ signin-img ] start -->
<div class="auth-wrapper align-items-stretch aut-bg-img">
    <div class="flex-grow-1">
        <!-- Left side -->
        <div class="h-100 d-md-flex align-items-center auth-side-img">
            <div class="col-sm-10 auth-content w-auto">
                <img src="{{ asset('assets/images/auth/auth-logo.png') }}" alt="Logo" class="img-fluid">
                <h1 class="text-white my-4">Welcome Back, Admin!</h1>
                <h4 class="text-white font-weight-normal">
                    Sign in to access the administration dashboard.<br/>
                </h4>
            </div>
        </div>

        <!-- Right side (form) -->
        <div class="auth-side-form">
            <div class="auth-content">
                <img src="{{ asset('assets/images/auth/auth-logo-dark.png') }}" alt="" class="img-fluid mb-4 d-block d-xl-none d-lg-none">
                <h3 class="mb-4 f-w-400">Admin Sign In</h3>

                <!-- Display Session Status -->
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="floating-label" for="email">Email address</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="floating-label" for="password">Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="custom-control custom-checkbox text-left mb-4 mt-2">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                        <label class="custom-control-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-block btn-primary mb-4">Sign In</button>
                </form>

                <div class="text-center">
                    {{-- <p class="mb-2 mt-4 text-muted">
                        Forgot password? 
                        <a href="{{ route('password.request') }}" class="f-w-400">Reset</a>
                    </p> --}}
                    <p class="mb-0 text-muted">
                        Don’t have an account? 
                        <a href="{{ route('register') }}" class="f-w-400">Signup</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ signin-img ] end -->

<!-- Required Js -->
<script src="{{ asset('assets/js/vendor-all.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/ripple.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
</body>
</html>
