@extends('layout.default')

@section('title', 'Edit User')

@push('css')
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
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

    .form-control {
        border-radius: 8px;
        font-size: 14px;
    }

    .form-check-label {
        font-size: 13px;
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

    .role-badge {
        background: #eef1f5;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
    }

    #previewImage {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
    }

    #previewImageContainer {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #f0f0f0;
        border: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .camera-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #4e73df;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid #fff;
        transition: 0.3s ease;
    }

    .camera-btn:hover {
        background: #224abe;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Edit User <small class="text-muted">Update user information & roles</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-user-edit me-2"></i> Update User</h4>
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

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- ✅ Profile Image -->
            <div class="mb-3 text-center">
    <label class="form-label d-block">Profile Image</label>
    <div style="position: relative; display: inline-block;">
        @if($user->profile_image)
            <img id="previewImage" 
                 src="{{ asset('storage/'.$user->profile_image) }}?v={{ time() }}" 
                 alt="Profile Image" 
                 style="width:80px; height:80px; border-radius:50%; object-fit:cover;">
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
   

                    <!-- Camera Button -->
                    <label for="profile_image" class="camera-btn">
                        <i class="fas fa-camera" style="font-size:14px;"></i>
                    </label>
                </div>
                <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">
                @error('profile_image')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+966</span>
                        <input type="text" name="phone" value="{{ str_replace('+966', '', $user->phone) }}" class="form-control">
                    </div>
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password (leave blank if unchanged)</label>
                    <input type="password" name="password" class="form-control">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- ✅ Roles Checkboxes -->
            <div class="mb-4">
                <label class="form-label">Roles <span class="text-danger">*</span></label>
                <div class="card" style="border:1px solid #dee2e6; border-radius:8px;">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($allRoles as $index => $role)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" id="role_{{ $role->id }}"
                                        value="{{ $role->name }}" {{ in_array($role->name, $userRoles ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            </div>
                            @if(($index + 1) % 3 == 0)
                            <div class="w-100"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('users') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ✅ Instant Image Preview
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let previewImage = document.getElementById('previewImage');
            let previewContainer = document.getElementById('previewImageContainer');
            if (previewImage) {
                previewImage.src = e.target.result;
            } else if (previewContainer) {
                previewContainer.outerHTML = `<img id="previewImage" src="${e.target.result}" 
                    style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:2px solid #ddd;">`;
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>

@endsection
