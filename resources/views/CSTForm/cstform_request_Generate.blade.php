@extends('layout.default')

@section('title', 'CST Form')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>

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
@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@section('content')
<!-- page header -->
<h1 class="page-header">
    CST Form <small>.</small>
</h1>
  @include('partials.alerts')
<div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; background:rgba(255,255,255,0.8);">
    <div class="d-flex justify-content-center align-items-center" style="height:100%;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="formControls" class="mb-5">
            <div class="card" style="border: none; border-bottom: 1px solid #dee2e6;">
                <div class="card-body pb-2">
                    <form method="post" action="{{route('cstform.store')}}" enctype="multipart/form-data" id="cstForm">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="requesterName">Requester Name</label>
                                    <input type="text" class="form-control" id="requesterName" placeholder="Enter name"
                                        value="{{Auth::user()->name ?? ''}}" disabled>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="name@example.com"
                                        value="{{Auth::user()->email ?? ''}}" disabled>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                @if(auth()->user()->hasRole('Team'))

                                <div class="form-group mb-3">
                                    <label class="form-label" for="jobTitle">Job Title</label>
                                    <input type="text" class="form-control" id="jobTitle" placeholder="Enter job title"
                                        value="{{ Auth::user()->teamDetail->position ?? '' }}" disabled>
                                </div>
                                @endif
                                <div class="form-group mb-3">
                                    <label class="form-label" for="contactNumber">Contact Number</label>
                                    <input type="text" class="form-control" id="contactNumber"
                                        placeholder="+966 541234567" value="{{Auth::user()->phone ?? ''}}" disabled>
                                </div>
                            </div>
                            @if(auth()->user()->hasRole('Team'))
                            <div class="form-group mb-3">
                                <label class="form-label" for="contactNumber">Select User</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="user_id" required>
                                    <option value="">-- select --</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->email}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>

                        <!-- Divider -->
                        <hr>
                        <h5 class="mb-3">Field Test Details</h5>

                        <!-- Field Test Details -->


                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="exampleFormControlSelect1">Request Type</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="request_type">
                                    <option value="">-- Request Type --</option>
                                    <option>New Test</option>
                                    <option>Re Test</option>

                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="exampleFormControlSelect1">Test Type</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="test_type">
                                    <option value="">-- Select Type --</option>
                                    <option>Drive Test</option>
                                    <option>Walk Test</option>
                                    <option>Drive and Walk Test</option>
                                </select>
                            </div>
                            {{-- Region --}}
                            <div class="col-md-4 mb-3">
                                <label for="regionSelect" class="form-label">Select Region:</label>
                                <select id="regionSelect" class="form-select" name="region" required>
                                    <option value="">-- Choose Region --</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->region }}">{{ $region->region }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- City (depends on region) --}}
                            <div class="col-md-4 mb-3">
                                <label for="citySelect" class="form-label">Select City:</label>
                                <select id="citySelect" class="form-select" name="area" disabled required>
                                    <option value="">-- Choose City --</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="exampleFormControlSelect1">Severity</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="severity">
                                    <option value="">-- Select --</option>
                                    <option>High</option>
                                    <option>Medium</option>
                                    <option>Low</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="exampleFormControlSelect1">Activity Type</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="activity_type">
                                    <option value="">-- Select --</option>
                                    <option>Highway</option>

                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="exampleFormControlSelect1">Operator</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="operator">
                                    <option value="">-- Select --</option>
                                    <option>Mobility</option>
                                    <option>STC</option>
                                    <option>Zain</option>
                                    <option>All</option>

                                </select>
                            </div>

                            <div class="col-md-4 mb-3 position-relative">
                                <label class="form-label" for="latitude">Latitude</label>
                                <input type="text" class="form-control ps-4" id="latitude" name="latitude" placeholder="Latitude">
                                <i class="fa fa-map-marker-alt text-secondary" style="position:absolute; left:18px; top:40px; pointer-events:none;"></i>
                            </div>

                            <div class="col-md-4 mb-3 position-relative">
                                <label class="form-label" for="longitude">Longitude</label>
                                <input type="text" class="form-control ps-4" id="longitude" name="longitude" placeholder="Longitude">
                                <i class="fa fa-map-marker-alt text-secondary" style="position:absolute; left:18px; top:40px; pointer-events:none;"></i>
                            </div>


                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="pixelid">Pixel <sub>(optional)</sub></label>
                                    <select class="form-select" id="pixelid" name="pixel_id">
                                        <option value="">-- Select --</option>
                                        @foreach($pixels->groupBy('region') as $region => $items)
                                            <optgroup label="{{ $region }}">
                                                @foreach($items as $p)
                                                    <option value="{{ $p->grid_id }}"
                                                        {{ old('pixel_id', $model->pixel_id ?? null) == $p->id ? 'selected' : '' }}>
                                                        {{ $p->grid_id }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Scenario Type -->
                                <div class="col-md-4 mb-3">
                                    <label for="scenarioType" class="form-label">Scenario Type:</label>
                                    <select id="scenarioType" class="form-select" name="scenario_type">
                                        <option value="">-- Select --</option>
                                        @foreach($scenarios as $scenario)
                                        <option value="{{$scenario->scenario_type}}">{{$scenario->scenario_type}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Scenario Set -->
                                <div class="col-md-4 mb-3">
                                    <label for="scenarioSet" class="form-label">Scenario Set</label>
                                    <select id="scenarioSet" class="form-select" name="scenario_set">
                                        <option value="">-- Choose Mode --</option>
                                        <option value="default">Run As Default Scenarios</option>
                                        <option value="custom">Custom Scenarios</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dynamic Rows -->
                            <div id="dynamicRows" style="display: none;"></div>

                            <div id="customActions" class="mb-2" style="display:none;">
                                <button type="button" id="addRowBtn" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus me-1"></i> Add more
                                </button>
                            </div>

                            <!-- Row template (hidden) -->
                            <script type="text/template" id="scenarioRowTemplate">
                                <div class="row align-items-start g-3 mb-3 scenario-row" data-index="__INDEX__" id="row__INDEX__">
                                    <!-- enable/disable row -->
                                    <div class="col-12 d-flex align-items-center gap-2">
                                        <input type="checkbox" class="form-check-input row-check" id="check__INDEX__" onchange="toggleRow(__INDEX__)">
                                        <label class="form-check-label" for="check__INDEX__">Enable row</label>
                                    </div>

                                    <!-- Scenario -->
                                    <div class="col-md-4">
                                        <label class="form-label" for="main__INDEX__">Scenario</label>
                                        <input type="text" class="form-control main" id="main__INDEX__"
                                               name="scenarios[__INDEX__][scenario]" placeholder="e.g., Data, Coverage, Voice">
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-4">
                                        <label class="form-label" for="desc__INDEX__">Description</label>
                                        <input type="text" class="form-control desc" id="desc__INDEX__"
                                               name="scenarios[__INDEX__][description]" placeholder="e.g., DL, UL, Social Media">
                                    </div>

                                    <!-- Network -->
                                    <div class="col-md-4">
                                        <label class="form-label" for="net__INDEX__">Network</label>
                                        <input type="text" class="form-control net" id="net__INDEX__"
                                               name="scenarios[__INDEX__][network]" placeholder="e.g., Auto Mode, 2G/3G/4G">
                                    </div>

                                    <!-- Duration -->
                                    <div class="col-md-4">
                                        <label class="form-label" for="dur__INDEX__">Duration</label>
                                        <input type="text" class="form-control dur" id="dur__INDEX__"
                                               name="scenarios[__INDEX__][duration]" placeholder="e.g., 60s, 300s, Continuous">
                                    </div>

                                    <!-- Number of devices -->
                                    <div class="col-md-4">
                                        <label class="form-label" for="dev__INDEX__">Number of Devices</label>
                                        <input type="text" class="form-control dev" id="dev__INDEX__"
                                               name="scenarios[__INDEX__][device]" placeholder="e.g., 3">
                                    </div>

                                    <!-- Pause / Cause -->
                                    <div class="col-md-4">
                                        <label class="form-label" for="extra__INDEX__">Pause / Cause</label>
                                        <input type="text" class="form-control extra" id="extra__INDEX__"
                                               name="scenarios[__INDEX__][cause]" placeholder="e.g., 5s, NA">
                                    </div>

                                    <div class="col-12 d-flex gap-2">
                                        <button class="btn btn-danger btn-sm row-reset" type="button" onclick="resetRow(__INDEX__)">Reset</button>
                                        <button class="btn btn-lime btn-sm row-remove" type="button" onclick="removeRow(__INDEX__)">Remove</button>
                                    </div>
                                </div>
                            </script>
                        </div>
                        <div class="form-group mb-3">
                            <label for="testDetails" class="form-label">Test Details</label>
                            <textarea class="form-control" id="teamDetails" rows="5"
                                placeholder="Enter test details here..." name="test_details"></textarea>
                        </div>
                        <hr>
                        <h5 class="mb-3">Routes</h5>
                        <button type="button" class="btn btn-primary mb-3" onclick="toggleRouteView()"> <i
                                class="fa fa-map me-1"></i> Generate Route</button>

                        <!-- Hidden Include Section -->
                        <div id="routeSection" style="display: none;">
                            @include('UserRoute.index')
                        </div>

                        <div class="form-group mb-3">
                            <label for="simpleText" class="form-label">Route Link</label>
                            <input type="text" class="form-control" id="simpleText" placeholder="Route Link"
                                name="route_link" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="simpleText" class="form-label">Upload Kml (optional)</label>
                            <input type="file" class="form-control" id="simpleText" placeholder="Route Link"
                                name="kml_path[]" readonly multiple accept=".kml,.kmz,.gpx,.xml">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="exampleFormControlSelect1">Route Distance</label>
                            <select class="form-select" id="exampleFormControlSelect1" name="route_distance">
                                <option value="">-- Select --</option>
                                <option>Less than 300km</option>
                                <option>More than 300km</option>

                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="teamDetails" class="form-label">Route Details</label>
                            <textarea class="form-control" id="teamDetails" rows="5"
                                placeholder="Enter route details here..." name="route_details"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="docs" class="form-label">Attachments (optional)</label>
                            <input type="file"
                                   class="form-control"
                                   id="docs"
                                   name="docs[]"
                                   multiple>
                        </div>
                <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('cstform') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Submit
                </button>
            </div>
                    </div>



            </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('cstForm');
        if (form) {
            form.addEventListener('submit', function () {
                document.getElementById('loader').style.display = 'block';
            });
        }
    });

    function toggleRouteView() {
    const routeSection = document.getElementById('routeSection');
    if (routeSection.style.display === 'none') {
        routeSection.style.display = 'block';
        // Trigger map resize after showing the section
        setTimeout(() => {
            if (window.map) {
                window.map.invalidateSize();
            }
        }, 100);
    } else {
        routeSection.style.display = 'none';
    }
}

// Hide route section when clicked
document.addEventListener('DOMContentLoaded', function() {
    const routeSection = document.getElementById('routeSection');
    if (routeSection) {
        routeSection.addEventListener('click', function() {
            this.style.display = 'none';
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('citySelect');
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            if (this.selectedIndex === -1) return;
            const selectedOption = this.options[this.selectedIndex];
            const latitude = selectedOption.getAttribute('data-lat');
            const longitude = selectedOption.getAttribute('data-lng');
            document.getElementById('latitude').value = latitude ?? '';
            document.getElementById('longitude').value = longitude ?? '';
        });
    }
});
</script>
<script>
    $(function () {
        // ---- state ----
        const scenarioPools = { scenarios: [], byScenario: {} };
        let currentMode = '';   // 'default' | 'custom'
        let nextIndex = 1;

        function toggleCustomActions(show) { $('#customActions').toggle(!!show); }

        // join unique, trimmed values to a CSV string
        function toCSV(values) {
            if (!values) return '';
            if (!Array.isArray(values)) return String(values ?? '').trim();
            const seen = new Set();
            return values
                .map(v => String(v ?? '').trim())
                .filter(v => (seen.has(v) ? false : (seen.add(v), true)))
                .join(', ');
        }

        // Build a row from template
        function buildRow(idx) {
            const html = $('#scenarioRowTemplate').html().replaceAll('__INDEX__', idx);
            $('#dynamicRows').append(html);
            $(`#row${idx}`).attr('data-index', idx);
        }

        // Prefill inputs from a pool entry (response item)
        function hydrateRow(idx, entry, {prefill = true} = {}) {
            const row = $(`#row${idx}`);
            const scenario = prefill ? (entry.scenario ?? '') : '';

            row.find(`#main${idx}`).val(scenario);
            row.find(`#desc${idx}`).val(prefill ? toCSV(entry.description) : '');
            row.find(`#net${idx}`).val(prefill ? toCSV(entry.network) : '');
            row.find(`#dur${idx}`).val(prefill ? toCSV(entry.duration) : '');
            row.find(`#dev${idx}`).val(prefill ? (Array.isArray(entry.device) ? (entry.device[0] ?? '') : String(entry.device ?? '')) : '');
            row.find(`#extra${idx}`).val(prefill ? toCSV(entry.cause) : '');

            // enable/disable buttons per mode
            if (currentMode === 'default') {
                $(`#check${idx}`).prop('checked', true);
                row.find('input').prop('disabled', false);
                $(`#btn${idx}`).prop('disabled', false);
            } else {
                $(`#check${idx}`).prop('checked', false);
                row.find('input').prop('disabled', false);
                $(`#btn${idx}`).prop('disabled', true);
            }
        }

        // add / remove / reset / toggle
        window.addRow = function (entry = null) {
            const idx = nextIndex++;
            buildRow(idx);
            if (!entry) entry = scenarioPools.scenarios[0] ?? {scenario:'', description:[], network:[], duration:[], device:[], cause:[]};
            hydrateRow(idx, entry, {prefill: (currentMode === 'default')});
        };

        window.removeRow = function (idx) {
            $(`#row${idx}`).remove();
            reindexRows();
        };

        window.toggleRow = function (i) {
            const enabled = $(`#check${i}`).is(':checked');
            $(`#row${i}`).find('input').prop('disabled', !enabled);
            $(`#btn${i}`).prop('disabled', !enabled);
        };

        window.resetRow = function (i) {
            const scenarioName = $(`#main${i}`).val().trim();
            const entry = scenarioPools.byScenario[scenarioName] || scenarioPools.scenarios[0] || {scenario:'', description:[], network:[], duration:[], device:[], cause:[]};
            hydrateRow(i, entry, {prefill: true});
        };

        function reindexRows() {
            let n = 1;
            $('#dynamicRows .scenario-row').each(function () {
                const $row = $(this);
                const old = $row.data('index');

                function fix(idPrefix, nameSuffix) {
                    const $el = $row.find(`#${idPrefix}${old}`);
                    $el.attr('id', `${idPrefix}${n}`);
                    if (nameSuffix) $el.attr('name', `scenarios[${n}]${nameSuffix}`);
                }

                $row.attr('id', `row${n}`).attr('data-index', n);

                // checkbox
                const $chk = $row.find(`#check${old}`);
                $chk.attr('id', `check${n}`).attr('onchange', `toggleRow(${n})`);

                // inputs
                fix('main',  '[scenario]');
                fix('desc',  '[description]');
                fix('net',   '[network]');
                fix('dur',   '[duration]');
                fix('dev',   '[device]');
                fix('extra', '[cause]');

                // buttons
                $row.find(`button[onclick="resetRow(${old})"]`).attr('onclick', `resetRow(${n})`).attr('id', `btn${n}`);
                $row.find(`button[onclick="removeRow(${old})"]`).attr('onclick', `removeRow(${n})`);

                n++;
            });
            nextIndex = n;
        }

        // Scenario Type -> fetch and prefill rows (same endpoint you already have)
        $('#scenarioType').on('change', function () {
            const scenarioType = $(this).val();
            if (!scenarioType) {
                $('#scenarioSet').val('');
                $('#dynamicRows').hide().empty();
                toggleCustomActions(false);
                return;
            }

            $('#scenarioSet').val('default').trigger('change');
            $('#dynamicRows').empty().show();
            toggleCustomActions(false);

            $.ajax({
                url: '{{ route("get.checkpoint.options") }}',
                type: 'GET',
                data: { scenario_type: scenarioType },
                success: function (response) {
                    // response is an array like:
                    // [{scenario, description[], network[], duration[], cause[], device[]}, ...]
                    scenarioPools.scenarios = response || [];
                    scenarioPools.byScenario = {};
                    scenarioPools.scenarios.forEach(r => scenarioPools.byScenario[r.scenario] = r);

                    // one row per entry, prefilled
                    $('#dynamicRows').empty();
                    nextIndex = 1;
                    scenarioPools.scenarios.forEach(entry => addRow(entry));

                    applyDefaultMode();
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });

        // Default vs Custom
        $('#scenarioSet').on('change', function () {
            const mode = $(this).val();
            currentMode = mode;

            if (mode === 'default') {
                applyDefaultMode();
                toggleCustomActions(false);
            } else if (mode === 'custom') {
                applyCustomMode();
                toggleCustomActions(true);
            } else {
                toggleCustomActions(false);
            }
        });

        $('#addRowBtn').on('click', function () { addRow(); });

        function applyDefaultMode() {
            currentMode = 'default';
            $('#dynamicRows .scenario-row').each(function () {
                const id = $(this).data('index');
                resetRow(id); // prefill from pool
            });
            toggleCustomActions(false);
        }

        function applyCustomMode() {
            currentMode = 'custom';
            $('#dynamicRows .scenario-row').each(function () {
                const id = $(this).data('index');
                $(`#check${id}`).prop('checked', false).prop('disabled', false);
                $(`#btn${id}`).prop('disabled', true);
                $(this).find('input').prop('disabled', false);
            });
            toggleCustomActions(true);
        }
    });
</script>

<script>
    $(function () {
        // In case you're using jQuery AJAX with CSRF:
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // ensure you have the csrf token meta in <head>
            }
        });

        const $region = $('#regionSelect');
        const $city   = $('#citySelect');
        const $lat    = $('#latitude');
        const $lon    = $('#longitude');

        // Reset city & coords
        function resetCityAndCoords(disableCity = true) {
            $city.empty()
                .append(new Option('-- Choose City --', ''))
                .prop('disabled', !!disableCity);
            $lat.val('');
            $lon.val('');
        }

        // On region change: fetch cities
        $region.on('change', function () {
            const region = $(this).val();
            resetCityAndCoords(true);

            if (!region) return;

            $.get('{{ route("regions.cities") }}', { region })
                .done(function (rows) {
                    // rows: [{area, lat, lon}, ...]
                    rows.forEach(r => {
                        const opt = new Option(r.area, r.area, false, false);
                        // store coords in data-*
                        $(opt).attr('data-lat', r.lat ?? '')
                            .attr('data-lon', r.lon ?? '');
                        $city.append(opt);
                    });
                    $city.prop('disabled', rows.length === 0);
                })
                .fail(function (xhr) {
                    console.error('Failed to load cities:', xhr.responseText);
                    resetCityAndCoords(true);
                });
        });

        // On city change: fill lat/lon from option data-*
        $city.on('change', function () {
            const opt = this.options[this.selectedIndex];
            const lat = opt.getAttribute('data-lat') || '';
            const lon = opt.getAttribute('data-lon') || '';
            $lat.val(lat);
            $lon.val(lon);
        });

        // (Optional) Select2
        // $('#regionSelect, #citySelect').select2({ width: '100%', placeholder: 'Select...' });
    });
</script>

<script>
    $(document).ready(function() {
        $('#pixelid, #regionSelect, #citySelect').select2({
            width: '100%'
        });

        // Pixel ID change handler
        $('#pixelid').on('change', function() {
            const pixelId = $(this).val();
            if (!pixelId) return;

            $.ajax({
                url: '{{ route("get.pixel.details") }}',
                type: 'GET',
                data: { pixel_id: pixelId },
                success: function(response) {
                    // Set Region
                    if (response.region) {
                        $('#regionSelect').val(response.region).trigger('change');

                        // Wait for cities to load then set city
                        // We need to wait for the AJAX call in the region change handler to complete
                        // A simple timeout is unreliable. A better way is to use a promise or check if options are populated.

                        // Let's use a polling mechanism to check if the city select has options
                        let attempts = 0;
                        const maxAttempts = 20; // 2 seconds max

                        const checkCityOptions = setInterval(function() {
                            attempts++;
                            const citySelect = $('#citySelect');

                            // Check if options are loaded (more than just the default placeholder)
                            if (citySelect.find('option').length > 1) {
                                clearInterval(checkCityOptions);

                                if (response.city) {
                                    // Try to find the city option
                                    const cityOption = citySelect.find(`option[value="${response.city}"]`);

                                    if (cityOption.length > 0) {
                                        citySelect.val(response.city).trigger('change');

                                        // Manually set lat/long if available in the response (from pixel table)
                                        // This overrides the city default lat/long if pixel has specific coordinates
                                        if (response.lat) $('#latitude').val(response.lat);
                                        if (response.lon) $('#longitude').val(response.lon);
                                    } else {
                                        console.warn(`City "${response.city}" not found in dropdown for region "${response.region}"`);
                                        // Fallback: if city not found in dropdown, maybe just set lat/long from pixel
                                        if (response.lat) $('#latitude').val(response.lat);
                                        if (response.lon) $('#longitude').val(response.lon);
                                    }
                                }
                            } else if (attempts >= maxAttempts) {
                                clearInterval(checkCityOptions);
                                console.error('Timeout waiting for city options to load');
                            }
                        }, 100);
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching pixel details:', xhr);
                }
            });
        });
    });
</script>

@endsection
