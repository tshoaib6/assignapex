@extends('layout.default')

@section('title', 'Region & City List')

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
    Region & City List <small class="text-muted">Manage all regions and cities</small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-map-marker-alt me-2"></i> Regions & Cities</h4>
        <a href="{{ route('region.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add Region & City
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
                    <th>Region</th>
                    <th>Area</th>
                    <th>Cities / Highway</th>
                    <th>Test Type</th>
                    <th>LAT</th>
                    <th>LON</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($regions as $index => $region)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $region->region }}</td>
                    <td>{{ $region->area }}</td>
                    <td>{{ $region->city_highway }}</td>
                    <td>{{ $region->test_type }}</td>
                    <td>{{ $region->lat }}</td>
                    <td>{{ $region->lon }}</td>
                    <td>
                        <a href="{{ route('region.edit', $region->id) }}">
                            <i class="fa fa-edit text-success" title="Edit"></i>
                        </a>
                        <a href="{{ route('region.destroy', $region->id) }}"
                           onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this record?')) {document.getElementById('delete-form-{{ $region->id }}').submit();}">
                            <i class="fa fa-trash text-danger ms-2" title="Delete"></i>
                        </a>
                        <form id="delete-form-{{ $region->id }}" action="{{ route('region.destroy', $region->id) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
