@extends('layout.default')

@section('title', 'Add Check Point')

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


    .form-control {
        font-size: 14px;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Add New Check Point <small class="text-muted">Create a new post-processor check point</small>
</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('post-processor-checklists.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Section <span class="text-danger">*</span></label>
                <input type="text" name="section" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Parent Title</label>
                <input type="text" name="parent_title" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Check Point <span class="text-danger">*</span></label>
                <input type="text" name="check_point" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="Yes">✅ Yes</option>
                    <option value="No">❌ No</option>
                    <option value="N/A" selected>N/A</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control" rows="3"></textarea>
            </div>


            <!-- ✅ Buttons (Same Row) -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime"
                    onclick="window.location.href='{{ route('post-processor-checklists.index') }}'">
                    <i class="fa fa-times me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Save
                </button>
            </div>
           
        </form>
    </div>
</div>

@endsection
