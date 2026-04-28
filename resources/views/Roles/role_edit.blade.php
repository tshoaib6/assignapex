@extends('layout.default')

@section('title', 'Edit Role')

@push('css')
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
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
        font-weight: 500;
        color: #495057;
    }

    .form-control {
        border-radius: 6px;
        font-size: 14px;
    }

    .form-check-label {
        font-size: 14px;
    }

    .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
        padding: 6px 14px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96);
    }

    .btn-lime {
        background: #d1e7dd;
        color: #0f5132;
        border: none;
        padding: 6px 14px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-lime:hover {
        background: #badbcc;
        color: #0f5132;
    }

    .alert-warning {
        border-radius: 6px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Edit Role <small class="text-muted">Update role details & permissions</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-user-shield me-2"></i> Update Role</h4>
    </div>

    <div class="card-body">
        {{-- ✅ Validation Errors --}}
        @if ($errors->any())
        <div class="alert alert-warning">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- ✅ Role Name -->
            <div class="mb-3">
                <label class="form-label">Role Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-control" required>
                @error('name')
                <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- ✅ Permissions -->
            <div id="checkboxes" class="mb-4">
                <label class="form-label">Permissions <span class="text-danger">*</span></label>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($allPermissions as $index => $permission)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        id="permission_{{ $permission->id }}" value="{{ $permission->name }}"
                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>

                            {{-- ✅ Force row break after every 3 --}}
                            @if(($index + 1) % 3 == 0)
                            <div class="w-100"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime"
                    onclick="window.location.href='{{ url('roles') }}'">
                    <i class="fa fa-times me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
