@extends('layout.default')

@section('title', 'Add Team Member')

@push('css')
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

    .alert {
        border-radius: 6px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Add Team Member <small class="text-muted">Assign users to departments & positions</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-user-plus me-2"></i> New Team Member</h4>
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

        <form action="{{ route('team.store') }}" method="POST">
            @csrf

            <!-- ✅ Select User -->
            <div class="mb-3">
                <label class="form-label">Select User <span class="text-danger">*</span></label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Select User --</option>
                    @foreach($teamUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- ✅ Department -->
            <div class="mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department" class="form-select" required>
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>

            <!-- ✅ Position -->
            <div class="mb-3">
                <label class="form-label">Position <span class="text-danger">*</span></label>
                <select name="position" class="form-select" required>
                    <option value="">-- Select Position --</option>
                    @foreach($positions as $position)
                    <option value="{{ $position }}">{{ $position }}</option>
                    @endforeach
                </select>
            </div>

           <!-- ✅ Buttons (SAME AS OTHERS) -->

             <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ route('team.index') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Submit
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
