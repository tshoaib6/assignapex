{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


@extends('layout.empty')

@section('title', 'Register')

@section('content')

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="row w-100">

        <!-- Left Side (Image) -->
        <div class="col-md-6 d-none d-md-block p-0">
            <div class="h-100" style="background: url('/assets/img/login/login.png') center center / contain no-repeat;">
            </div>
        </div>

        <!-- Right Side (Form) -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="w-100" style="max-width: 480px;">  <!-- ✅ Reduced max width for better alignment -->

                <form name="register_form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
    @csrf

    <!-- Logo -->
    <div class="text-center mb-3">
        @include('partials.alerts')
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="max-width: 140px;">
    </div>

    <!-- Profile Image -->
    <div class="text-center mb-3">
        <label for="profile_image">
            <div id="avatarContainer" style="
                width: 80px; height: 80px; border-radius: 50%;
                background-color: #f0f0f0; display: flex;
                align-items: center; justify-content: center;
                cursor: pointer; border: 2px solid #ddd; margin: 0 auto;">
                <i class="fas fa-user" style="font-size:30px; color:#888;"></i>
            </div>
        </label>
        <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;">
        @error('profile_image')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <!-- Name -->
    <div class="mb-2">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg fs-15px" name="name" value="{{ old('name') }}" required>
        @error('name')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-2">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control form-control-lg fs-15px" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone -->
    <div class="mb-3">
        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">+966</span>
            <input type="text" class="form-control form-control-lg fs-15px" name="phone"  required>
        </div>
        @error('phone')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-2">
        <label class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control form-control-lg fs-15px" name="password" required>
        @error('password')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control form-control-lg fs-15px" name="password_confirmation" required>
    </div>

    <!-- Terms -->
    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="customCheck1" required>
            <label class="form-check-label fw-500" for="customCheck1">
                I agree to the <a href="/legal/apexassign">Terms of Use</a> & <a href="/legal/apexassign">Privacy Policy</a>.
            </label>
        </div>
    </div>

    <!-- Submit -->
    <div class="mb-3">
        <button type="submit" class="btn btn-theme btn-lg fs-15px fw-500 w-100">Sign Up</button>
    </div>

    <div class="text-muted text-center">
        Already have a User ID? <a href="{{ route('login') }}">Sign In</a>
    </div>
</form>

            </div>
        </div>
    </div>
</div>

<!-- ✅ Font Awesome + JS Preview -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script>
    // ✅ Replace FA icon with uploaded image preview
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarContainer').innerHTML =
                `<img src="${e.target.result}" 
                      style="width:100%; height:100%; border-radius:50%; object-fit:cover;">`;
        }
        reader.readAsDataURL(event.target.files[0]);
    });
</script>
@endsection
