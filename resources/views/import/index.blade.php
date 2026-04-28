@extends('layout.default')

@section('title', 'Import Data')

@section('content')
<h1 class="page-header">Import Data</h1>

<div class="row">
    <!-- Import Pixels -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Import Pixels</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('import.pixels') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="pixelFile" class="form-label">Upload CSV File</label>
                        <input type="file" class="form-control" id="pixelFile" name="file" required accept=".csv">
                        <div class="form-text">Required columns: grid_id, region, city, lat, lon</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Import Pixels</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Regions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Import Regions & Cities</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('import.regions') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="regionFile" class="form-label">Upload CSV File</label>
                        <input type="file" class="form-control" id="regionFile" name="file" required accept=".csv">
                        <div class="form-text">Required columns: region, area, city_highway, test_type, lat, lon</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Import Regions</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif

@endsection
