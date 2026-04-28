@extends('layout.default')

@section('title', 'Scenarios List')

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
        padding: 6px 14px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96);
    }

    .table {
        font-size: 14px;
        border-radius: 8px;
        overflow: hidden;
    }

    .table th {
        background: #f1f3f5;
        font-weight: 600;
        color: #495057;
    }

    .fa {
        cursor: pointer;
        transition: 0.2s ease;
    }

    .fa:hover {
        opacity: 0.8;
        transform: scale(1.1);
    }

    .alert {
        border-radius: 6px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Scenarios List <small class="text-muted">Manage and organize all scenarios</small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-tasks me-2"></i> Scenario Records</h4>
        <a href="{{ route('scenarios.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add Scenario
        </a>
    </div>

    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Scenario Type</th>
                    <th>Scenario</th>
                    <th>Description</th>
                    <th>Network</th>
                    <th>Duration</th>
                    <th>Pause</th>
                    <th>No. of Devices</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scenarios as $index => $scenario)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $scenario->scenario_type }}</td>
                    <td>{{ $scenario->scenario }}</td>
                    <td>{{ $scenario->description }}</td>
                    <td>{{ $scenario->network }}</td>
                    <td>{{ $scenario->duration }}</td>
                    <td>{{ $scenario->pause }}</td>
                    <td>{{ $scenario->number_of_devices }}</td>
                    <td>
                        <a href="{{ route('scenarios.edit', $scenario->id) }}">
                            <i class="fa fa-edit text-success" title="Edit"></i>
                        </a>
                        <form action="{{ route('scenarios.destroy', $scenario->id) }}" 
                              method="POST" 
                              style="display:inline" 
                              onsubmit="return confirm('Are you sure you want to delete this scenario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="border:none; background:none; padding:0;">
                                <i class="fa fa-trash text-danger ms-2" title="Delete"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">No scenarios found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
