@extends('layout.default')

@section('title', 'Add Checklist')

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
    Add Checklist <small class="text-muted">Fill in the required details</small>
</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('checklists.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Section <span class="text-danger">*</span></label>
                <select name="section" class="form-select" required>
                    <option value="">Select Section</option>
                    <option value="Plan and Tools">Plan and Tools</option>
                    <option value="Field Observation">Field Observation</option>
                    <option value="Voice KPIs">Voice KPIs</option>
                    <option value="DATA Devices">DATA Devices</option>
                    <option value="Scanner (2G/3G/4G/5G)">Scanner (2G/3G/4G/5G)</option>
                    <option value="Start Activity ODO Meter Picture">Start Activity ODO Meter Picture</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Check Point <span class="text-danger">*</span></label>
                <input type="text" name="check_point" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="">Select Status</option>
                    <option value="Yes">✅ Yes</option>
                    <option value="No">❌ No</option>
                    <option value="N/A">N/A</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Image (Optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

        
          <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ route('checklists.index') }}'">
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
