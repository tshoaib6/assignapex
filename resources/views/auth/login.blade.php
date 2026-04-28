@extends('layout.empty')

@section('title', 'Login')

@section('content')
<!-- BEGIN login -->
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="row w-100" style="max-width: 100%;  overflow: hidden;">
        
        <!-- Left Side - Image (60%) -->
        <div class="col-md-7 d-none d-md-block p-0">
            
            <div class="h-100" style="background: url('/assets/img/login/login.png') center center / contain no-repeat;">
            </div>
        </div>

        <!-- Right Side - Login Form (40%) -->
        <div class="col-md-5  d-flex align-items-center">
         
            <div class="login-content p-4 p-md-5 w-100">
                <form method="post" action="{{ route('login') }}" name="login_form">
                    @csrf
                    <div class="text-center mb-4">
                           @include('partials.alerts')
    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="max-width: 160px;">
</div>
                    <div class="text-muted text-center mb-4">
                        For your protection, please verify your identity.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                      <input type="email" 
       name="email" 
       class="form-control form-control-lg fs-15px"  placeholder="username@address.com" required        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
       title="Please enter a valid email address (e.g., username@example.com)"></div>
                   <div class="mb-3">
    <div class="d-flex">
        <label class="form-label">Password</label>
        <a href="{{ route('password.request') }}" class="ms-auto text-muted">Forgot password?</a>
    </div>
    
    <div class="input-group">
        <input type="password" class="form-control form-control-lg fs-15px" name="password" id="password" placeholder="Enter your password" required>
        <span class="input-group-text bg-white" style="cursor: pointer;" onclick="togglePassword()" id="toggleIcon">
            <i class="fa fa-eye-slash"></i>
        </span>
    </div>

    @error('password')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="customCheck1">
                            <label class="form-check-label fw-500" for="customCheck1">Remember me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-theme btn-lg w-100 fw-500 mb-3">Sign In</button>
                    <div class="text-center text-muted">
                        Don't have an account yet? <a href="{{ route('register') }}">Sign up</a>.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon').firstElementChild;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    }
</script>

<!-- END login -->
@endsection
