@extends('layout.default')

@section('title', 'Change Password')

@push('css')
@endpush

@section('content')
<div class="container mt-4">
    @include('partials.alerts')

    <div class="card">
        <div class="card-header">
            <h4>Change Password</h4>
        </div>
        <div class="card-body">
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

                <button type="submit" class="btn btn-primary">Update Password</button>
                <a href="{{ route('profile.show') }}" class="btn btn-link">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
