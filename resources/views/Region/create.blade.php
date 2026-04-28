@extends('layout.default')

@section('title', 'Add Region & City')

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

</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <h4><i class="fa fa-map-marker-alt me-2"></i> Add Region & City</h4>

        <form action="{{ route('region.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Region <span class="text-danger">*</span></label>
                <input type="text" name="region" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Area <span class="text-danger">*</span></label>
                <input type="text" name="area" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Cities / Highway <span class="text-danger">*</span></label>
                <input type="text" name="city_highway" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Test Type (Outdoor)</label>
                <input type="text" name="test_type" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Latitude (LAT)</label>
                <input type="text" name="lat" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Longitude (LON)</label>
                <input type="text" name="lon" class="form-control">
            </div>

            <!-- ✅ Buttons aligned to right, same as Team Add/Edit -->
              <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime me-2" onclick="window.location.href='{{ route('region.index') }}'">
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
