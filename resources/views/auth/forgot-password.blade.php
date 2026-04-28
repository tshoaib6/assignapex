

@extends('layout.empty')

@section('title', 'Forget Password')

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
                <form method="post" action="{{ route('password.email') }}" >
                    @csrf
                    <div class="text-center mb-4">
                           @include('partials.alerts')
    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="max-width: 160px;">
</div>
                    <div class="text-muted text-center mb-4">
                        Forgot your password? No problem. Enter your email to get a reset link.
                    </div>
                   <!-- Email Address -->
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" 
                            name="email" 
                            class="form-control form-control-lg fs-15px" 
                            placeholder="username@address.com" 
                            required 
                            value="{{ old('email') }}"
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                            title="Please enter a valid email address (e.g., username@example.com)">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-theme btn-lg w-100 fw-500 mb-3">
                        Send Password Reset Link
                    </button>

                    <div class="text-center text-muted">
                        Remember your password? <a href="{{ route('login') }}">Back to Login</a>.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END login -->
@endsection









