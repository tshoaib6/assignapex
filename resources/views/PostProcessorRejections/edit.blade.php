@extends('layout.default')

@section('title', 'Edit Post Processor Rejection')

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
        margin-bottom: 20px;
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

    .btn-secondary {
        border-radius: 8px;
        font-size: 14px;
        padding: 6px 14px;
    }

    .text-danger {
        font-size: 13px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Edit Post Processor Rejection <small class="text-muted">Update rejection issue</small>
</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('post_processor_rejections.update', $postProcessorRejection->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Field Issue <span class="text-danger">*</span></label>
                <input type="text" name="field" class="form-control" value="{{ $postProcessorRejection->field }}" required>
                @error('field')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

           <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='{{ route('post_processor_rejections.index') }}'">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
