@extends('layout.default')

@section('title', 'Edit Profile')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
    .profile-wrapper {
        max-width: 1000px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        padding: 2rem;
    }
    .form-label {
        font-weight: 600;
        color: #374151;
    }
    .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 8px;
    }
    .profile-img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #ddd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
  .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96);
    }

    .btn-lime {
        background: #e2e6ea;
        color: #495057;
        border-radius: 8px;
        font-size: 14px;
    }

    .btn-lime:hover {
        background: #d6d8db;
    }
</style>
@endpush

@section('content')


    <div class="card">
    @include('partials.alerts')
        <div class="card-header">
            <h4>Edit Informatiom</h4>
        </div><div class="card-body">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="row align-items-center">
            <!-- LEFT: Profile Image -->
            <div class="col-md-4 text-center mb-4 mb-md-0">
                

                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="profile-img mb-3">
                @else
                       <div id="previewImageContainer" 
                 style="width:80px; height:80px; border-radius:50%; background:#ddd; 
                        display:flex; align-items:center; justify-content:center; 
                        font-weight:bold; color:#555; font-size:22px;">
                @php
                    $nameParts = explode(' ', trim($user->name));
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                @endphp
                {{ $initials }}
            </div>
                @endif

                <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror">
                @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- RIGHT: Profile Fields -->
            <div class="col-md-8">
 

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user me-1"></i> Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope me-1"></i> Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-phone me-1"></i> Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', ltrim($user->phone, '+966')) }}"
                        class="form-control @error('phone') is-invalid @enderror" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
 <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ route('profile.show') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Update
                </button>
            </div>
               
            </div>
        </div>
    </form>
    </div>
    </div>


  <div class="card" style="margin-top: 40px">
    @include('partials.alerts')
        <div class="card-header">
            <h4>Change Password</h4>
        </div><div class="card-body">
       <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf

                <div class="mb-3">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
 <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ route('profile.show') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Update
                </button>
            </div>
               
            </form>
    </div>
    </div>



@endsection
