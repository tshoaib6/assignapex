@extends('layout.default')

@section('title', 'Edit Scenario')

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

    .alert {
        border-radius: 6px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Edit Scenario <small class="text-muted">Update scenario details</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-edit me-2"></i> Update Scenario</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('scenarios.update', $scenario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Scenario Type</label>
                <select name="scenario_type" class="form-select" required>
                    @foreach($scenarioTypes as $type)
                        <option value="{{ $type }}" {{ $scenario->scenario_type == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Scenario</label>
                <input type="text" name="scenario" value="{{ $scenario->scenario }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="description" value="{{ $scenario->description }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Network</label>
                <input type="text" name="network" value="{{ $scenario->network }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Duration</label>
                <input type="text" name="duration" value="{{ $scenario->duration }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Pause</label>
                <input type="text" name="pause" value="{{ $scenario->pause }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Number of Devices</label>
                <input type="number" name="number_of_devices" value="{{ $scenario->number_of_devices }}" class="form-control">
            </div>



  <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ route('scenarios.index') }}'">
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
