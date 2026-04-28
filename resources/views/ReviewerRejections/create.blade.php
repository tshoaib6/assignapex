@extends('layout.default')

@section('title', 'Add Reviewer Rejection')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .card-body h4 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
        padding: 6px 16px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96);
    }

    .btn-secondary {
        background: #e2e6ea;
        color: #333;
        border: none;
        padding: 6px 16px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-secondary:hover {
        background: #d6d8db;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Add Reviewer Rejection <small class="text-muted">Create a new rejection issue</small>
</h1>

<div class="card">
    <div class="card-body">
        <h4><i class="fa fa-plus-circle me-2 text-primary"></i>New Reviewer Rejection</h4>

        <form action="{{ route('reviewer_rejections.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="Field">Field</option>
                    <option value="Report">Report</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Issue <span class="text-danger">*</span></label>
                <input type="text" name="issue" class="form-control" placeholder="Enter issue" required>
            </div>
 <!-- Action Buttons -->
          
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-secondary me-2"
                        onclick="window.location.href='{{ route('reviewer_rejections.index') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
