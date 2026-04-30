@extends('layout.default')

@section('title', 'Edit CST Request')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
.btn-primary { background: linear-gradient(45deg,#4e73df,#224abe); border:none; padding:8px 18px; font-size:14px; border-radius:8px; }
.btn-lime { background:#e2e6ea; color:#495057; border-radius:8px; font-size:14px; }
</style>
@endpush

@section('content')
<h1 class="page-header">Edit CST Request <small class="text-muted">{{ $cstRequest->unique_request_id }}</small></h1>

@include('partials.alerts')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('cstform.request.update', $cstRequest->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Request Type</label>
                    <select class="form-select" name="request_type" required>
                        <option value="">-- Select --</option>
                        @foreach(['New Test','Re Test'] as $opt)
                            <option value="{{ $opt }}" {{ $cstRequest->request_type == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Test Type</label>
                    <select class="form-select" name="test_type" required>
                        <option value="">-- Select --</option>
                        @foreach(['Drive Test','Walk Test','Drive and Walk Test'] as $opt)
                            <option value="{{ $opt }}" {{ $cstRequest->test_type == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="regionSelect" class="form-label">Region</label>
                    <select id="regionSelect" class="form-select" name="region" required>
                        <option value="">-- Choose Region --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->region }}" {{ $cstRequest->region == $region->region ? 'selected' : '' }}>
                                {{ $region->region }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="citySelect" class="form-label">City</label>
                    <select id="citySelect" class="form-select" name="area">
                        <option value="">-- Choose City --</option>
                        @if($cstRequest->city)
                            <option value="{{ $cstRequest->city }}" selected>{{ $cstRequest->city }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Severity</label>
                    <select class="form-select" name="severity" required>
                        <option value="">-- Select --</option>
                        @foreach(['High','Medium','Low'] as $opt)
                            <option value="{{ $opt }}" {{ $cstRequest->severity == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Activity Type</label>
                    <select class="form-select" name="activity_type" required>
                        <option value="">-- Select --</option>
                        <option value="Highway" {{ $cstRequest->activity_type == 'Highway' ? 'selected' : '' }}>Highway</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Operator</label>
                    <select class="form-select" name="operator" required>
                        <option value="">-- Select --</option>
                        @foreach(['Mobility','STC','Zain','All'] as $opt)
                            <option value="{{ $opt }}" {{ $cstRequest->operator == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" name="latitude" id="latitude" value="{{ $cstRequest->latitude }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" name="longitude" id="longitude" value="{{ $cstRequest->longitude }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pixel ID</label>
                    <select id="pixelid" class="form-select" name="pixel_id">
                        <option value="">-- Select --</option>
                        @foreach($pixels->groupBy('region') as $regionName => $items)
                            <optgroup label="{{ $regionName }}">
                                @foreach($items as $p)
                                    <option value="{{ $p->grid_id }}" {{ $cstRequest->pixel == $p->grid_id ? 'selected' : '' }}>
                                        {{ $p->grid_id }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Scenario Type</label>
                    <select class="form-select" name="scenario_type">
                        <option value="">-- Select --</option>
                        @foreach($scenarios as $sc)
                            <option value="{{ $sc->scenario_type }}" {{ $cstRequest->scenario_type == $sc->scenario_type ? 'selected' : '' }}>
                                {{ $sc->scenario_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Route Distance</label>
                    <select class="form-select" name="route_distance">
                        <option value="">-- Select --</option>
                        @foreach(['Less than 300km','More than 300km'] as $opt)
                            <option value="{{ $opt }}" {{ $cstRequest->route_distance == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Test Details</label>
                <textarea class="form-control" name="test_details" rows="4">{{ $cstRequest->test_details }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Route Link</label>
                <input type="text" class="form-control" name="route_link" value="{{ $cstRequest->route_link }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Route Details</label>
                <textarea class="form-control" name="route_details" rows="4">{{ $cstRequest->route_details }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('cstform.index') }}" class="btn btn-lime">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    $('#pixelid, #regionSelect, #citySelect').select2({ width: '100%' });

    const savedCity = '{{ $cstRequest->city }}';

    function loadCities(region, selectVal) {
        if (!region) return;
        $.get('{{ route("regions.cities") }}', { region })
            .done(function(rows) {
                $('#citySelect').empty().append(new Option('-- Choose City --', ''));
                rows.forEach(r => {
                    const opt = new Option(r.area, r.area);
                    $(opt).attr('data-lat', r.lat || '').attr('data-lon', r.lon || '');
                    $('#citySelect').append(opt);
                });
                if (selectVal) $('#citySelect').val(selectVal).trigger('change');
                $('#citySelect').prop('disabled', rows.length === 0);
            });
    }

    // Load cities for current region on page load
    const initRegion = $('#regionSelect').val();
    if (initRegion) loadCities(initRegion, savedCity);

    $('#regionSelect').on('change', function() {
        loadCities($(this).val(), null);
    });

    $('#citySelect').on('change', function() {
        const opt = this.options[this.selectedIndex];
        const lat = opt.getAttribute('data-lat') || '';
        const lon = opt.getAttribute('data-lon') || '';
        if (lat) $('#latitude').val(lat);
        if (lon) $('#longitude').val(lon);
    });

    $('#pixelid').on('change', function() {
        const pixelId = $(this).val();
        if (!pixelId) return;
        $.get('{{ route("get.pixel.details") }}', { pixel_id: pixelId }, function(resp) {
            if (resp.region) {
                $('#regionSelect').val(resp.region).trigger('change');
                if (resp.lat) $('#latitude').val(resp.lat);
                if (resp.lon) $('#longitude').val(resp.lon);
                setTimeout(function() {
                    if (resp.city) $('#citySelect').val(resp.city).trigger('change');
                }, 600);
            }
        });
    });
});
</script>
@endpush
