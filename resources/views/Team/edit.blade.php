@extends('layout.default')

@section('title', 'Edit Team Member')

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


    .form-label {
        font-weight: 600;
        color: #495057;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Edit Team Member <small class="text-muted">Update existing team details</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-user-edit me-2"></i> Update Member Info</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('team.update', $teamDetail->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Select User</label>
                <select name="user_id" class="form-select" required>
                    @foreach($teamUsers as $user)
                        <option value="{{ $user->id }}" {{ $teamDetail->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department" class="form-select" required>
                    @foreach($departments as $department)
                        <option value="{{ $department }}" {{ $teamDetail->department == $department ? 'selected' : '' }}>
                            {{ $department }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Position</label>
                <select name="position" class="form-select" required>
                    @foreach($positions as $position)
                        <option value="{{ $position }}" {{ $teamDetail->position == $position ? 'selected' : '' }}>
                            {{ $position }}
                        </option>
                    @endforeach
                </select>
            </div>


  <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ route('team.index') }}'">
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
