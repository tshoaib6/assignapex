@include('partial.head')
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


.btn-primary {
    background: linear-gradient(45deg, #4e73df, #224abe) !important;
    border: none;
    padding: 8px 18px;
    font-size: 14px;
    border-radius: 8px;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #224abe, #1b3c96) !important;
}

.btn-lime {
    background: #e2e6ea !important;
    color: #495057;
    border-radius: 8px;
    font-size: 14px;
}

.btn-lime:hover {
    background: #d6d8db !important;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

@media print {

    .no-print,
    .no-print * {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
    }
}
</style>
@endpush

<div class="minn m-3 p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0">Final Report</h4>

        <div class="no-print" id="printButtonWrapper">
            <button class="btn btn-primary" onclick="printPage()">
                <i class="fa fa-print me-1"></i> Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- Request Information & Tester Assignment Display -->
    @php
    $request = \App\Models\CSTRequest::with(['user.teamDetail'])->find($cstid);
    $scenariosSelected = \App\Models\SelectedScenario::where('cst_request_id', $cstid)->get();
    @endphp

    @php
        function asArray($v) {
            if (is_null($v) || $v === '') return [];
            return is_array($v) ? $v : array_filter(preg_split('/\s*,\s*/', (string)$v));
        }
    @endphp

    <!-- Request Info (Collapsible) -->
    @if($request->step >= 1)
    <div class="card">
        <div class="card-header">
            <i class="fa fa-info-circle"></i> Request Information
        </div>
        <div class="card-body">
            <!-- User Info -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" value="{{ $request->user->name ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Job Title</label>
                    <input type="text" class="form-control" value="{{ $request->user->teamDetail->position ?? 'N/A' }}"
                        disabled>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $request->user->email ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact</label>
                    <input type="text" class="form-control" value="{{ $request->user->phone ?? 'N/A' }}" disabled>
                </div>
            </div>

            <!-- Request Meta -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Request Type</label>
                    <input type="text" class="form-control" value="{{ $request->request_type }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Test Type</label>
                    <input type="text" class="form-control" value="{{ $request->test_type }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Region</label>
                    <input type="text" class="form-control" value="{{ $request->region }}" disabled>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Severity</label>
                    <input type="text" class="form-control" value="{{ $request->severity }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Activity Type</label>
                    <input type="text" class="form-control" value="{{ $request->activity_type }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Operator</label>
                    <input type="text" class="form-control" value="{{ $request->operator }}" disabled>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Scenario Type</label>
                    <input type="text" class="form-control" value="{{ $request->scenario_type }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Scenario Set</label>
                    <input type="text" class="form-control" value="{{ $request->scenario_set }}" disabled>
                </div>
            </div>

            <!-- Scenarios -->
            @php
            $scenariosSelected = \App\Models\SelectedScenario::where('cst_request_id', $cstid)->get();
            @endphp
            @if($scenariosSelected->count())
            <h6 class="info-section-title mt-4">Selected Scenarios</h6>
            @foreach($scenariosSelected as $index => $scenario)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto d-flex align-items-center mt-4">
                            <input class="form-check-input me-2" type="checkbox" checked disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Scenario</label>
                            <input type="text" class="form-control" value="{{ $scenario->scenario }}" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Network</label>
                            <input type="text" class="form-control" value="{{ $scenario->network }}" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" value="{{ $scenario->description }}" disabled>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" value="{{ $scenario->duration }}" disabled>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Pause</label>
                            <input type="text" class="form-control" value="{{ $scenario->pause }}" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Devices</label>
                            <input type="text" class="form-control" value="{{ $scenario->devices }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif

            <!-- Location & Route -->
            <h6 class="info-section-title mt-4">Test Location</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" value="{{ $request->latitude }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" value="{{ $request->longitude }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Test Details</label>
                    <input type="text" class="form-control" value="{{ $request->test_details }}" disabled>
                </div>
            </div>

            @if($request->kml_path)
                @foreach($request->kml_path as $i => $path)
                    <a href="{{ asset('storage/app/public/kml_files/' . $path) }}"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-outline-primary">
                        Attachment {{ $i + 1 }}
                    </a>
                @endforeach
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Route Link</label>
                    <a href="{{ $request->route_link }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fa fa-external-link-alt me-1"></i> Open Route
                    </a>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Route Distance</label>
                    <input type="text" class="form-control" value="{{ $request->route_distance }}" disabled>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Route Description</label>
                <textarea class="form-control" rows="3" disabled>{{ $request->route_details }}</textarea>
            </div>

            {{-- General docs uploaded in the request form --}}
            @include('partials._attachments', [
                'title' => 'Request Attachments',
                'files' => asArray($request->docs)
            ])
        </div>
    </div>
    @endif


    @if($request->step >= 3)

    <!-- Assigned Tester -->
    <div class="card">
        <div class="card-header" data-bs-toggle="collapse" href="#testerAssignmentCollapse" role="button"
            aria-expanded="true" aria-controls="testerAssignmentCollapse">
            <i class="fa fa-user-check"></i> Assigned Tester
        </div>
        <div class="collapse show" id="testerAssignmentCollapse">
            <div class="card-body">
                @php
                $assignment = \App\Models\TesterAssignment::with('tester')->where('cst_request_id', $cstid)->first();
                @endphp
                @if($assignment)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tester Name</label>
                        <input type="text" class="form-control" value="{{ $assignment->tester->name ?? 'N/A' }}"
                            disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Contact</label>
                        <input type="text" class="form-control" value="{{ $assignment->tester->phone ?? 'N/A' }}"
                            disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $assignment->tester->email ?? 'N/A' }}"
                            disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Team Leader's Note</label>
                    <textarea class="form-control" rows="3" disabled>{{ $assignment->note }}</textarea>
                </div>
                @else
                <p class="text-muted">No tester assigned yet.</p>
                @endif
            </div>
        </div>
    </div>
    @endif


    {{-- Driver --}}
    {{-- ==================== DRIVER CHECKLIST ===================== --}}
    @if($request->step >=4)
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa fa-clipboard-check me-2"></i> Driver Checklist Summary
        </div>

        @php
        $check_list = \App\Models\SelectedChecklist::where('cst_request_id', $cstid)->first();
        $checklistItems = collect();

        if ($check_list && $check_list->checklist_id) {
        $checklistIds = json_decode($check_list->checklist_id, true);
        if (is_array($checklistIds)) {
        $checklistItems = \App\Models\Checklist::whereIn('id', $checklistIds)->get();
        }
        }

        $planTools = $checklistItems->where('section', 'Plan & Tools');
        $otherSections = $checklistItems->where('section', '!=', 'Plan & Tools')->groupBy('section');
        @endphp

        <div class="card-body">

            {{-- ✅ Plan & Tools --}}
            @if($planTools->count())
            <h6 class="text-primary">Plan & Tools</h6>
            <ul class="list-group mb-3">
                @foreach($planTools as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item->check_point }}
                    <span class="badge bg-light border text-dark d-flex align-items-center gap-2">
                        <input type="checkbox" class="form-check-input" checked disabled>
                        <span>Selected</span>
                    </span>
                </li>
                @endforeach
            </ul>
            @endif

            {{-- ✅ Other Sections --}}
            @foreach($otherSections as $section => $items)
            <h6 class="text-primary mt-4">{{ $section }}</h6>
            <ul class="list-group mb-3">
                @foreach($items as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item->check_point }}
                    <span class="badge bg-light border text-dark d-flex align-items-center gap-2">
                        <input type="checkbox" class="form-check-input" checked disabled>
                        <span>Selected</span>
                    </span>
                </li>
                @endforeach
            </ul>
            @endforeach

            {{-- ✅ ODO Meter --}}
            <h6 class="mt-3">ODO Meter</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Start KM</label>
                    <input type="text" class="form-control" value="{{ $check_list->starting_km ?? '' }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">End KM</label>
                    <input type="text" class="form-control" value="{{ $check_list->ending_km ?? '' }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total KM</label>
                    <input type="text" class="form-control" value="{{ $check_list->total_km ?? '' }} KM" disabled>
                </div>
            </div>

            {{-- ✅ ODO Pictures --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Start ODO Picture</label><br>
                    @if($check_list->start_od_pic)
                    <img src="{{ asset('storage/app/public/' . $check_list->start_od_pic) }}" class="img-fluid rounded border"
                        style="max-height: 200px;">
                    @else
                    <p class="text-muted">No start picture uploaded</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">End ODO Picture</label><br>
                    @if($check_list->end_od_pic)
                    <img src="{{ asset('storage/app/public/' . $check_list->end_od_pic) }}" class="img-fluid rounded border"
                        style="max-height: 200px;">
                    @else
                    <p class="text-muted">No end picture uploaded</p>
                    @endif
                </div>
            </div>

            @php
                $checklistDocs = $check_list?->docs ?? [];
            @endphp
            @include('partials._attachments', [
                'title' => 'Driver Checklist Attachments',
                'files' => asArray($checklistDocs)
            ])

        </div>
    </div>
    @endif

    <!-- Test Result -->


    @php

    $testResult = \App\Models\FieldTestResult::where('cst_request_id', $cstid)->first();
    $user = Auth::user();
    $role = $user->getRoleNames()->first();
    @endphp
    {{-- ===================== FIELD TEST RESULT ===================== --}}
    @if($request->step >=5)
    <div class="card mb-5">
        <form action="{{ route('fieldtest.store') }}" method="POST">
            @csrf
            <input type="hidden" name="cst_request_id" value="{{ $request->id }}">

            <div class="card-body">
                <h5 class="mb-3">Field Test Results</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Test Start Time</label>
                        <input type="datetime-local" class="form-control" name="start_time" id="testStart" disabled
                            value="{{$testResult->start_time }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Field Team’s Notes and Observation</label>
                    <textarea class="form-control" name="notes" rows="3" disabled>{{$testResult->notes}}</textarea>
                </div>

                @php
                    $ftrDocs = $testResult?->docs ?? [];
                @endphp
                @include('partials._attachments', [
                    'title' => 'Field Test Attachments',
                    'files' => asArray($ftrDocs)
                ])

            </div>
        </form>
    </div>
    @endif
    {{-- ===================== Test Log File ===================== --}}
    @php

    $testlog = \App\Models\TestLogFile::where('cst_request_id', $cstid)->first();

    @endphp
    @if($request->step >= 6)
    <div id="fifthForm" style=" margin-top:20px;">
        <div class="card">
            <form action="{{ route('logfile.test.store') }}" method="POST">
                @csrf
                {{-- <input type="hidden" name="cst_request_id" value="{{$id}}"> --}}
                <div class="card-body">
                    <h5 class="mb-3">Test Log File Link</h5>

                    <div class="row">
                        <!-- Test Log File Link -->
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Test Log File Link</label>
                            <textarea name="log_file_link" class="form-control" rows="2"
                                placeholder="Paste the log file link here..."
                                disabled> {{$testlog->file_link }}</textarea>
                        </div>

                        <!-- Test Log File Quantity -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Test Log File Quantity</label>
                            <input type="number" name="log_file_quantity" class="form-control"
                                placeholder="Enter quantity" value="{{ $testlog->file_quantity }}" @disabled(true)>
                        </div>
                    </div>

                    @php
                        $logDocs = $testlog?->docs ?? [];
                    @endphp
                    @include('partials._attachments', [
                        'title' => 'Log File Attachments',
                        'files' => asArray($logDocs)
                    ])

                </div>
            </form>

        </div>
    </div>
    @endif


    @if($request->step >= 7)
    @php
    $ppcheck = \App\Models\CstPostProcessor::where('cst_request_id', $request->id)->first();
    $selectedChecklistIds = $ppcheck->checklist_ids ?? [];

    // Fetch selected checklist data grouped by section
    $checklistdata = \App\Models\PostProcessorChecklist::whereIn('id', $selectedChecklistIds)
    ->get()
    ->groupBy('section');
    @endphp

    <div class="card mt-4">
        <h5 class="m-3">Post Processor Checklist </h5>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width:30%;">Section</th>
                        <th style="width:50%;">Check Points</th>
                        <th style="width:10%;">Check</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checklistdata as $section => $items)
                    @foreach($items as $index => $checklist)

                    @if (!empty($checklist->parent_title))
                    <tr>
                        @if ($index === 0)
                        <td rowspan="{{ count($items) + 1 }}" class="fw-bold align-middle text-center">
                            {{ $section }}
                        </td>
                        @endif
                        <td colspan="2" class="fw-semibold">{{ $checklist->parent_title }}</td>
                    </tr>
                    @endif

                    <tr>
                        @if ($index === 0 && empty($checklist->parent_title))
                        <td rowspan="{{ count($items) }}" class="fw-bold align-middle text-center">
                            {{ $section }}
                        </td>
                        @endif
                        <td>{{ $checklist->check_point }}</td>
                        <td>
                            <input type="checkbox" class="form-check-input" disabled checked>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>

            @php
                $ppChecklistDocs = $ppcheck?->docs ?? [];
            @endphp
            @include('partials._attachments', [
                'title' => 'Post Processor Checklist Attachments',
                'files' => asArray($ppChecklistDocs)
            ])
        </div>
    </div>
    @endif

    @php
    $user = Auth::user();
    $position = optional($user->teamDetail)->position;
    @endphp


    @if($request->step >= 8)
    @php
    $datavalidation = \App\Models\PPdataValidation::where('cst_request_id', $request->id)->first();
    @endphp

    <div id="seventhForm" style="margin-top: 20px;">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Post Processor Data Validation</h5>

                @if ($datavalidation)
                {{-- Read-only display --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Post Processor's Decision</label>
                        <input type="text" class="form-control" value="{{ ucfirst($datavalidation->decision) }}"
                            readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Post Processor's Notes</label>
                    <textarea class="form-control" rows="4" readonly>{{ $datavalidation->notes }}</textarea>
                </div>
                @endif

                @php
                    $ppdvDocs = $datavalidation?->docs ?? [];
                @endphp
                @include('partials._attachments', [
                    'title' => 'Data Validation Attachments',
                    'files' => asArray($ppdvDocs)
                ])
            </div>
        </div>
    </div>

    @endif


    @if($request->step >= 9)
    @php
    $reportvalidation = \App\Models\PostProcessorReport::where('cst_request_id', $request->id)->first();
    @endphp
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Post Processor Report</h5>

            <!-- Hidden CST Request ID -->
            <input type="hidden" name="cst_request_id" value="{{ $request->id }}">

            <div class="form-group mb-3">
                <label class="form-label">Report Link</label>
                <textarea class="form-control" name="report_link" rows="2" placeholder="Paste the report link here..."
                    required>{{$reportvalidation->report_link}}</textarea>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Post Processor's Notes</label>
                <textarea class="form-control" name="notes" rows="3"
                    placeholder="Add any notes about the report...">{{$reportvalidation->notes}}</textarea>
            </div>

            @php
                $pprDocs = $reportvalidation?->docs ?? [];
            @endphp
            @include('partials._attachments', [
                'title' => 'Report Attachments',
                'files' => asArray($pprDocs)
            ])
        </div>
    </div>
    @endif


    @if($request->step >= 10)
    @php
    $reportvalidation = \App\Models\PostProcessorFinalChecklistConfirmation::where('cst_request_id',
    $request->id)->first();
    @endphp
    <div class="card shadow-sm rounded mb-4">
        <div class="card-body">
            <h5 class="mb-3">Post Processor Final Checklist Confirmation</h5>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Checklist Confirmation</label>
                    <select class="form-select" name="checklist_confirmation" disabled>
                        <option value="">-- Select --</option>
                        <option value="confirmed"
                            {{ $reportvalidation?->checklist_confirmation == 'confirmed' ? 'selected' : '' }}>Confirmed
                        </option>
                        <option value="not_confirmed"
                            {{ $reportvalidation?->checklist_confirmation == 'not_confirmed' ? 'selected' : '' }}>Not
                            Confirmed</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Actual KM</label>
                    <input type="number" class="form-control" name="actual_km" readonly
                        value="{{ $reportvalidation?->actual_km ?? ($check_list->total_km ?? '') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Actual Hours</label>
                    <input type="text" class="form-control" name="actual_hours" readonly
                        value="{{ $reportvalidation?->actual_hours ?? ($testResult->working_hours ?? '') }}">
                </div>
            </div>

            @php
                $ppfcc = \App\Models\PostProcessorFinalChecklistConfirmation::where('cst_request_id', $request->id)->first();
            @endphp
            @include('partials._attachments', [
                'title' => 'Final Checklist Confirmation Attachments',
                'files' => asArray($ppfcc?->docs ?? [])
            ])
        </div>
    </div>

    @endif


    @if($request->step >= 11)
    @php
    $rpvalidation = \App\Models\PostProcessorReportValidation::where('cst_request_id', $request->id)->first();
    @endphp

    <form action="{{ route('submit.tenth.form') }}" method="POST">
        @csrf
        <input type="hidden" name="cst_request_id" value="{{ $request->id }}">

        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Post Processor Report Validation</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Post Processor's Decision</label>
                        @if($rpvalidation)
                        <input type="text" class="form-control"
                            value="{{ ucfirst($rpvalidation->report_validation_decision) }}" readonly>
                        @else
                        <select class="form-select" name="decision" required>
                            <option value="">-- Select Decision --</option>
                            <option value="accept" {{ old('decision') == 'accept' ? 'selected' : '' }}>Accept</option>
                            <option value="reject" {{ old('decision') == 'reject' ? 'selected' : '' }}>Reject</option>
                            <option value="review" {{ old('decision') == 'review' ? 'selected' : '' }}>Need Review
                            </option>
                        </select>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Post Processor's Notes</label>
                    @if($rpvalidation)
                    <textarea class="form-control" rows="3"
                        readonly>{{ $rpvalidation->report_validation_notes }}</textarea>
                    @else
                    <textarea class="form-control" name="notes" rows="3"
                        placeholder="Please add your notes if there is any...">{{ old('notes') }}</textarea>
                    @endif
                </div>

                {{-- Buttons removed completely if readonly --}}
                @if(!$rpvalidation)
                <div class="row justify-content-end mt-3">
                    <button type="button" class="btn btn-secondary me-2"
                        onclick="window.location.href='{{ url('cstform') }}'">Cancel</button>
                    <button type="submit" class="btn btn-lime me-2">Submit Tenth Form</button>
                </div>
                @endif

            </div>
        </div>
    </form>
    @endif



    @if($request->step >= 12)
    @php
    $rpvalidation = \App\Models\TeamLeaderEvaluation::where('cst_request_id', $request->id)->first();
    @endphp

    <div id="eleventhForm" style="margin-top:20px;">
        <form action="{{ route('team.leader.evaluation.submit') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Team Leader Evaluation</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Team Leader's Decision</label>
                            @if($rpvalidation)
                            <input type="text" class="form-control" value="{{ ucfirst($rpvalidation->decision) }}"
                                readonly>
                            @else
                            <select class="form-select" name="decision" id="teamLeaderDecision" required>
                                <option value="">-- Please Select --</option>
                                <option value="approve" {{ old('decision') == 'approve' ? 'selected' : '' }}>Approve
                                </option>
                                <option value="reject" {{ old('decision') == 'reject' ? 'selected' : '' }}>Reject
                                </option>
                                <option value="review" {{ old('decision') == 'review' ? 'selected' : '' }}>Need Review
                                </option>
                            </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Team Leader's Notes</label>
                        @if($rpvalidation)
                        <textarea class="form-control" rows="3" readonly>{{ $rpvalidation->notes }}</textarea>
                        @else
                        <textarea class="form-control" name="notes" id="teamLeaderNotes" rows="3"
                            placeholder="Please add your notes if there is any...">{{ old('notes') }}</textarea>
                        @endif
                    </div>

                    @php
                        $tleDocs = $rpvalidation?->docs ?? [];
                    @endphp
                    @include('partials._attachments', [
                        'title' => 'Team Leader Evaluation Attachments',
                        'files' => asArray($tleDocs)
                    ])
                </div>
            </div>
        </form>
    </div>
    @endif


    @if($request->step >= 13)
    @php
    $acceptance = \App\Models\CstFinalAcceptance::where('cst_request_id', $request->id)->first();
    @endphp

    <div id="twelfthForm" style="margin-top:20px;">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">CST Final Acceptance</h5>

                @if($acceptance)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CST's Decision</label>
                        <input type="text" class="form-control" value="{{ ucfirst($acceptance->decision) }}" readonly>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">CST's Notes</label>
                    <textarea class="form-control" rows="3" readonly>{{ $acceptance->notes }}</textarea>
                </div>
                @endif

                @php
                    $acceptDocs = $acceptance?->docs ?? [];
                @endphp
                @include('partials._attachments', [
                    'title' => 'Final Acceptance Attachments',
                    'files' => asArray($acceptDocs)
                ])
            </div>
        </div>
    </div>
    @endif
    </diiv>
    <script>
function printPage() {
    var btnDiv = document.getElementById('printButtonWrapper');
    btnDiv.style.display = 'none'; // Button ko hide karo

    // Print hone se pehle thoda delay do
    setTimeout(function () {
        window.print();

        // Print ke baad dobara button show karo (optional)
        setTimeout(function () {
            btnDiv.style.display = 'block';
        }, 1000);
    }, 300);
}
</script>
