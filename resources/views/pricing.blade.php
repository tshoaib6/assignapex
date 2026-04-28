@extends('layout.default')

@section('title', ' Update Pricing Configuration')

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
    Update Pricing Configuration <small class="text-muted"></small>
</h1>

<div class="card">
    <div class="card-body">
        <h4><i class=" me-2 text-primary"></i>Pricing Configuration</h4>
        <form action="{{ route('pricing.update', $pricing->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Driver Test Unit Cost</label>
                <input type="number" step="0.01" name="unit_cost_driver_test"
                    value="{{ $pricing->unit_cost_driver_test }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">WALK Test Unit Cost</label>
                <input type="number" step="0.01" name="unit_cost_walk_test" value="{{ $pricing->unit_cost_walk_test }}"
                    class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update Pricing</button>
        </form>
    </div>
</div>
@endsection