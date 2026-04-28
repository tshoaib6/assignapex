@extends('layout.default')

@section('title', 'Edit Checklist')

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
    Edit Checklist <small class="text-muted">Update the checklist details</small>
</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('checklists.update', $checklist->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Section <span class="text-danger">*</span></label>
                <select name="section" class="form-select" required>
                    <option value="Plan and Tools" {{ $checklist->section == 'Plan and Tools' ? 'selected' : '' }}>Plan and Tools</option>
                    <option value="Field Observation" {{ $checklist->section == 'Field Observation' ? 'selected' : '' }}>Field Observation</option>
                    <option value="Voice KPIs" {{ $checklist->section == 'Voice KPIs' ? 'selected' : '' }}>Voice KPIs</option>
                    <option value="DATA Devices" {{ $checklist->section == 'DATA Devices' ? 'selected' : '' }}>DATA Devices</option>
                    <option value="Scanner (2G/3G/4G/5G)" {{ $checklist->section == 'Scanner (2G/3G/4G/5G)' ? 'selected' : '' }}>Scanner (2G/3G/4G/5G)</option>
                    <option value="Start Activity ODO Meter Picture" {{ $checklist->section == 'Start Activity ODO Meter Picture' ? 'selected' : '' }}>Start Activity ODO Meter Picture</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Check Point <span class="text-danger">*</span></label>
                <input type="text" name="check_point" class="form-control" value="{{ $checklist->check_point }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="Yes" {{ $checklist->status == 'Yes' ? 'selected' : '' }}>✅ Yes</option>
                    <option value="No" {{ $checklist->status == 'No' ? 'selected' : '' }}>❌ No</option>
                    <option value="N/A" {{ $checklist->status == 'N/A' ? 'selected' : '' }}>N/A</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control" rows="3">{{ $checklist->remarks }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Image</label><br>
                @if($checklist->image)
                    <img src="{{ asset('storage/'.$checklist->image) }}" 
                         style="width:80px; height:80px; border-radius:5px; margin-bottom:5px; border:1px solid #ddd;">
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

              <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ route('checklists.index') }}'">
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
