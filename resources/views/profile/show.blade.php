@extends('layout.default')

@section('title', 'My Profile')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
    .profile-wrapper {
        max-width: 100%;
        margin: 0 auto;
        padding: 2rem;
    }
    .profile-card {
        background: #fff;
        padding: 2.5rem;
    }
    .form-label {
        font-weight: 600;
        color: #4b5563;
        font-size: 0.95rem;
    }
    .form-control-plaintext {
        background: #f8fafc;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 1.05rem;
    }
    .profile-img {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 1rem;
        color: #111827;
    }
    .profile-meta {
        color: #6b7280;
        font-size: 0.95rem;
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
    <div class="card-header">
            <h4>Profile Details</h4>
        </div>
    @include('partials.alerts')
<div class="card-body">
    <div class="row profile-card ">
        <!-- Left (Image, Name, Email) -->
        <div class="col-lg-4 text-center border-end">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" class="profile-img mb-3" alt="Profile Image">
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
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-meta">{{ $user->email }}</div>
        </div>

        <!-- Right (Details) -->
        <div class="col-lg-8">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-envelope me-1"></i> Email</label>
                    <div class="form-control-plaintext">{{ $user->email }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-phone me-1"></i> Phone</label>
                    <div class="form-control-plaintext">{{ $user->phone }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-user-tag me-1"></i> Role</label>
                    <div class="form-control-plaintext">{{ $user->getRoleNames()->first() ?? 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-briefcase me-1"></i> Position</label>
                    <div class="form-control-plaintext">{{ optional($user->team_details)->position ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-id-badge me-1"></i> User ID</label>
                    <div class="form-control-plaintext">{{ $user->id }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-calendar-alt me-1"></i> Joined</label>
                    <div class="form-control-plaintext">{{ $user->created_at->format('F d, Y') }}</div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button onclick="window.location.href='{{ route('profile.edit') }}'" class="btn btn-primary">
                    <i class="fa fa-edit me-1"></i> Edit Profile
                </button>
                <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-1"></i> Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div></div> 
</div>
@endsection
