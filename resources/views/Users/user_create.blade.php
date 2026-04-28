@extends('layout.default')

@section('title', 'Create User')

@push('css')
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .card {
        border: none;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
    }

    .card-header h4 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
        color: #495057;
    }

    .form-control-lg {
        font-size: 14px;
        border-radius: 8px;
    }

    .input-group-text {
        background: #f1f1f1;
        font-weight: bold;
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

    #avatarContainer {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid #ddd;
        transition: all 0.3s ease;
    }

    #avatarContainer:hover {
        border-color: #4e73df;
        transform: scale(1.05);
    }

    .text-danger.small {
        font-size: 12px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Create User <small class="text-muted">Add new users and assign roles</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-user-plus me-2"></i> New User</h4>
    </div>

    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-warning">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ url('users') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Profile Image Upload -->
            <div class="text-center mb-3">
                <label for="profile_image">
                    <div id="avatarContainer">
                        <i class="fas fa-user" style="font-size:34px; color:#888;"></i>
                    </div>
                </label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display:none;">
                @error('profile_image')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" name="name" value="{{ old('name') }}"
                        required>
                    @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}"
                        required>
                    @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">+966</span>
                        <input type="text" class="form-control form-control-lg" name="phone"
                            value="{{ old('phone') }}" required>
                    </div>
                    @error('phone')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="roles[]" class="form-select form-control-lg" required>
                        <option value="">-- Select Role --</option>
                        @foreach ($allRoles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-lg" name="password" required>
                    @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control form-control-lg" name="password_confirmation" required>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('users') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ✅ Live Preview for Profile Image
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
