@push('css')

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
</style>
@endpush

<!-- Request Information & Tester Assignment Display -->
@php
$request = \App\Models\CSTRequest::with(['user.teamDetail'])->find($cstid);
$scenariosSelected = \App\Models\SelectedScenario::where('cst_request_id', $cstid)->get();
@endphp

<!-- Request Info (Collapsible) -->
@if($request->step >= 1)
<div class="card">
    <div class="card-header" data-bs-toggle="collapse" data-bs-target="#requestInfoCollapse" role="button"
        aria-expanded="false" aria-controls="requestInfoCollapse">
        <i class="fa fa-info-circle"></i> Request Information
    </div>
    <div class="collapse" id="requestInfoCollapse">
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

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Route Link</label>
                    @if($request->route_link)
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ $request->route_link }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fa fa-external-link-alt me-1"></i> Open Route
                            </a>
                        </div>
                        <small class="text-muted">{{ $request->route_link }}</small>
                    @else
                        <span class="text-muted">No route link available</span>
                    @endif
                    @if($request->kml_path)
                        @foreach($request->kml_path as $i => $path)
                            <a href="{{ asset('storage/app/public/kml_files/' . $path) }}"
                               target="_blank" rel="noopener"
                               class="btn btn-sm btn-outline-primary">
                                Attachment {{ $i + 1 }}
                            </a>
                        @endforeach
                    @endif
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
            <div class="row mb-3">
                <div class="col-md-6">
                      <h6 class="info-section-title mt-4">Attachments Files</h6>

             @if(!empty($request->docs) && is_array($request->docs))
  <div class="d-flex flex-wrap gap-2">
    @foreach($request->docs as $i => $doc)
      <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"
         target="_blank" rel="noopener"
         class="btn btn-sm btn-outline-primary">
         Attachment {{ $i + 1 }}
      </a>
    @endforeach
  </div>
@else
  <p class="text-muted">No attachments uploaded.</p>
@endif</div>
</div>
        </div>


    </div>
</div>
@endif


@if($request->step >= 3)

<!-- Assigned Tester -->
<div class="card">
    <div class="card-header" data-bs-toggle="collapse" href="#testerAssignmentCollapse" role="button"
        aria-expanded="false" aria-controls="testerAssignmentCollapse">
        <i class="fa fa-user-check"></i> Assigned Tester
    </div>
    <div class="collapse" id="testerAssignmentCollapse">
        <div class="card-body">
            @php
            $assignment = \App\Models\TesterAssignment::with('tester')->where('cst_request_id', $cstid)->first();
            @endphp
            @if($assignment)
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tester Name</label>
                    <input type="text" class="form-control" value="{{ $assignment->tester->name ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contact</label>
                    <input type="text" class="form-control" value="{{ $assignment->tester->phone ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $assignment->tester->email ?? 'N/A' }}" disabled>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Team Leader's Note</label>
                <textarea class="form-control" rows="3" disabled>{{ $assignment->note }}</textarea>
            </div>
            @else
            <p class="text-muted">No tester assigned yet.</p>
            @endif
                <div class="row mb-3">
                <div class="col-md-6">
                      <h6 class="info-section-title mt-4">Attachments Files</h6>

             @if(!empty($assignment->docs) && is_array($assignment->docs))
  <div class="d-flex flex-wrap gap-2">
    @foreach($assignment->docs as $i => $doc)
      <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"
         target="_blank" rel="noopener"
         class="btn btn-sm btn-outline-primary">
         Attachment {{ $i + 1 }}
      </a>
    @endforeach
  </div>
@else
  <p class="text-muted">No attachments uploaded.</p>
@endif</div>
</div>
        </div>

    </div>
</div>
@endif


{{-- Driver --}}
{{-- ==================== DRIVER CHECKLIST ===================== --}}
@if($request->step >=4)
<div class="card mb-4">
    <div class="card-header" data-bs-toggle="collapse" href="#drivertestCollapse" role="button"
        aria-expanded="false" aria-controls="drivertestCollapse">
        <i class="fa fa-clipboard-check me-2"></i> Driver Checklist Summary
    </div>
 <div class="collapse" id="drivertestCollapse">
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
        @foreach($otherSections as $section => $items)
        <h6 class="text-primary mt-4">{{ $section }}</h6>
        <ul class="list-group mb-3">
            @foreach($items as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center" >
                {{ $item->check_point }}
                <span class="badge bg-light border text-dark d-flex align-items-center gap-2">
                    <input type="checkbox" class="form-check-input" checked disabled>
                    <span>Selected</span>
                </span>
            </li>
            @endforeach
        </ul>
        @endforeach

        <h6 class="mt-3">ODO Meter</h6>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Start KM</label>
                <input type="text" class="form-control" value="{{ $check_list->starting_km ?? '' }}" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">End KM</label>
                <input type="text" class="form-control" value="{{ $check_list->ending_km ?? '' }}" disabled>
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
                    <p class="text-muted">No start picture uploaded</p>
                @endif
            </div>
             <div class="col-md-6">
                      <h6 class="info-section-title mt-4">Attachments Files</h6>

             @if(!empty($check_list->docs) && is_array($check_list->docs))
  <div class="d-flex flex-wrap gap-2">
    @foreach($check_list->docs as $i => $doc)
      <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"
         target="_blank" rel="noopener"
         class="btn btn-sm btn-outline-primary">
         Attachment {{ $i + 1 }}
      </a>
    @endforeach
  </div>
@else
  <p class="text-muted">No attachments uploaded.</p>
@endif</div>
</div>


    </div>
</div>
</div>
@endif

<!-- Test Result -->


@php

    $testResult = \App\Models\FieldTestResult::where('cst_request_id', $cstid)->first();
    $user = Auth::user();
    $role = $user->getRoleNames()->first();

    if (!empty($testResult) && $testResult->checklist_id) {
        $postchecklistIds = json_decode($testResult->checklist_id, true);
        if (is_array($postchecklistIds)) {
            $postchecklistItems = \App\Models\Checklist::where('section', 'Field Observation')
                                    ->orderBy('id')
                                    ->get();
        }
        $postchecklistItem = $postchecklistItems->groupBy('section')->map(function ($items) {
            $byParent = $items->groupBy(fn ($i) => $i->parent_title ?: '__NO_PARENT__');
            $rowspan = 0;
            foreach ($byParent as $parentTitle => $list) {
                $rowspan += ($parentTitle === '__NO_PARENT__' ? 0 : 1) + $list->count();
            }

            return [
                'rowspan' => $rowspan,
                'parents' => $byParent,
            ];
        });
    }
@endphp
{{-- ===================== FIELD TEST RESULT ===================== --}}
@if($request->step >=5)
<div class="card ">

 <div class="card-header" data-bs-toggle="collapse" href="#fieldtestCollapse" role="button"
        aria-expanded="false" aria-controls="fieldtestCollapse">
        <i class="fa fa-binoculars me-2"></i> Field Test Result
    </div>
 <div class="collapse" id="fieldtestCollapse">


    <form action="{{ route('fieldtest.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="cst_request_id" value="{{ $request->id }}">

        <div class="card-body">

            @if(isset($postchecklistItem) && $postchecklistItem->count())
                @foreach($postchecklistItem as $section => $data)
                    <h6 class="text-primary mt-4">{{ $section }}</h6>
                    @foreach($data['parents'] as $parentTitle => $items)
                        @if($parentTitle !== '__NO_PARENT__')
                            <div class="fw-semibold mb-2">{{ $parentTitle }}</div>
                        @endif

                        <ul class="list-group mb-3">
                            @foreach($items as $check)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $check->check_point }}
                                    <span class="badge bg-light border text-dark d-flex align-items-center gap-2">
                                      <input type="checkbox" class="form-check-input" checked disabled>
                                      <span>Selected</span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                @endforeach
            @endif

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Test Start Time</label>
                    <input type="datetime-local" class="form-control" name="start_time" id="testStart" disabled
                        value="{{$testResult->start_time }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Test End Time</label>
                    <input type="datetime-local" class="form-control" name="end_time" id="testEnd" disabled
                        value="{{$testResult->end_time }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Test Duration (Hours)</label>
                    <input type="text" class="form-control" name="working_hours" id="testDuration" disabled
                        value="{{ $testResult->working_hours }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Field Team’s Notes and Observation</label>
                <textarea class="form-control" name="notes" rows="3" disabled>{{$testResult->notes}}</textarea>
            </div>
  <div class="col-md-6">
                      <h6 class="info-section-title mt-4">Attachments Files</h6>

             @if(!empty($testResult->docs) && is_array($testResult->docs))
  <div class="d-flex flex-wrap gap-2">
    @foreach($testResult->docs as $i => $doc)
      <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"
         target="_blank" rel="noopener"
         class="btn btn-sm btn-outline-primary">
         Attachment {{ $i + 1 }}
      </a>
    @endforeach
  </div>
@else
  <p class="text-muted">No attachments uploaded.</p>
@endif</div>
</div>


    </form>
 </div>
</div>
@endif
{{-- ===================== Test Log File ===================== --}}
@php

$testlog = \App\Models\TestLogFile::where('cst_request_id', $cstid)->first();

@endphp
@if($request->step >= 6)
<div id="fifthForm" >
    <div class="card">
        <div class="card-header" data-bs-toggle="collapse" href="#testlogCollapse" role="button"
        aria-expanded="false" aria-controls="testlogCollapse">
        <i class="fa fa-link me-2"></i> Test Log File Link
    </div>
 <div class="collapse" id="testlogCollapse">
        <form action="{{ route('logfile.test.store') }}" method="POST">
            @csrf
            {{-- <input type="hidden" name="cst_request_id" value="{{$id}}"> LINK --}}
            <div class="card-body">


                <div class="row">
                    <!-- Test Log File Link -->
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Test Log File Link</label>
                        <textarea name="log_file_link" class="form-control" rows="2"
                            placeholder="Paste the log file link here..." disabled> {{$testlog->file_link }}</textarea>
                    </div>

                    <!-- Test Log File Quantity -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Test Log File Quantity</label>
                        <input type="number" name="log_file_quantity" class="form-control" placeholder="Enter quantity"
                            value="{{ $testlog->file_quantity }}" @disabled(true)>
                    </div>
                </div>
 <div class="col-md-6">
                      <h6 class="info-section-title mt-4">Attachments Files</h6>

             @if(!empty($testlog->docs) && is_array($testlog->docs))
  <div class="d-flex flex-wrap gap-2">
    @foreach($testlog->docs as $i => $doc)
      <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"
         target="_blank" rel="noopener"
         class="btn btn-sm btn-outline-primary">
         Attachment {{ $i + 1 }}
      </a>
    @endforeach
  </div>
@else
  <p class="text-muted">No attachments uploaded.</p>
@endif</div>


            </div>
        </form>
 </div>
    </div>
</div>
@endif

@php
$user = Auth::user();
$position = optional($user->teamDetail)->position;
@endphp

@if($request->step == 8)
    @if($position === 'Post Processor')
        @php
            $datavalidation = \App\Models\PPdataValidation::where('cst_request_id', $request->id)->first();

            $postchecklists = \App\Models\PostProcessorChecklist::orderBy('section')
                    ->orderBy('parent_title')
                    ->orderBy('id')
                    ->get();
            $postchecklistfinal = $postchecklists->groupBy('section')->map(function ($items) {
                $byParent = $items->groupBy(fn ($i) => $i->parent_title ?: '__NO_PARENT__');
                $rowspan = 0;
                foreach ($byParent as $parentTitle => $list) {
                    $rowspan += ($parentTitle === '__NO_PARENT__' ? 0 : 1) + $list->count();
                }

                return [
                    'rowspan' => $rowspan,
                    'parents' => $byParent,
                ];
            });
        @endphp
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4><i class="fa fa-file-excel  me-2"></i> Report </h4>
                    <form action="{{ route('cst.revertToStep4', $request->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to send this back to Field Observation?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">
                            <i class="fa fa-undo me-1"></i> Send Back
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="{{ route('postprocessor.storereport') }}" method="POST" id="eighthForm" style="margin-top:20px;" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="cst_request_id" value="{{ $request->id }}" />

                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                            <tr>
                                <th style="width:20%;">Section</th>
                                <th style="width:40%;">Checkpoints</th>
                                <th style="width:10%;">Check</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($postchecklistfinal as $section => $data)
                                    @php $firstRowForSection = true; @endphp

                                    @foreach($data['parents'] as $parentTitle => $items)
                                        @if($parentTitle !== '__NO_PARENT__')
                                            <tr>
                                                @if($firstRowForSection)
                                                    <td rowspan="{{ $data['rowspan'] }}" class="fw-bold align-middle text-center">
                                                        {{ $section }}
                                                    </td>
                                                    @php $firstRowForSection = false; @endphp
                                                @endif
                                                <td colspan="3" class="fw-bold align-middle text-center" style="background: #ebeef4">
                                                    {{ $parentTitle }}
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach($items as $checklist)
                                            <tr>
                                                @if($firstRowForSection)
                                                    <td rowspan="{{ $data['rowspan'] }}" class="fw-bold align-middle text-center">
                                                        {{ $section }}
                                                    </td>
                                                    @php $firstRowForSection = false; @endphp
                                                @endif

                                                <td class="text-start">{{ $checklist->check_point }}</td>
                                                <td>
                                                    <input type="checkbox"
                                                           name="checklists[{{ $checklist->id }}][checked]"
                                                           class="form-check-input"
                                                        {{ $checklist->status === 'Yes' ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                        <div class="form-group mb-3">
                            <label class="form-label">Report Link</label>
                            <textarea class="form-control" name="report_link" rows="2" placeholder="Paste the report link here..." required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Post Processor's Notes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Add any notes about the report..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Actual KM</label>
                                <input type="number" class="form-control" name="actual_km" placeholder="Enter Actual KM" value="{{ $check_list->total_km ?? '' }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Actual Hours</label>
                                <input type="text" class="form-control" name="actual_hours" placeholder="Enter Actual Hours" value="{{ $testResult->working_hours }}">
                            </div>
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
                    </form>
                </div>
            </div>
    @endif
@endif

@if($request->step >= 10)
@php
    $reportvalidation = \App\Models\PostProcessorFinalChecklistConfirmation::where('cst_request_id', $request->id)->first();
    $ppReport = \App\Models\PostProcessorReport::where('cst_request_id', $request->id)->first();
    $postchecklists = \App\Models\PostProcessorChecklist::orderBy('section')
            ->orderBy('parent_title')
            ->orderBy('id')
            ->get();
    $postchecklistfinal = $postchecklists->groupBy('section')->map(function ($items) {
        // group inside a section by parent_title (use a sentinel for nulls)
        $byParent = $items->groupBy(fn ($i) => $i->parent_title ?: '__NO_PARENT__');

        // compute total rows this section will occupy:
        // each parent_title group adds: +1 header row (if has a title) + count(items)
        $rowspan = 0;
        foreach ($byParent as $parentTitle => $list) {
            $rowspan += ($parentTitle === '__NO_PARENT__' ? 0 : 1) + $list->count();
        }

        return [
            'rowspan' => $rowspan,
            'parents' => $byParent,
        ];
    });
@endphp
<div class="card shadow-sm rounded mb-4">
       <div class="card-header" data-bs-toggle="collapse" href="#PostProcessorCheclistConfirmCollapse" role="button"
        aria-expanded="false" aria-controls="PostProcessorCheclistConfirmollapse">
        <i class="fa fa-check me-2"></i> Report
    </div>
     <div class="collapse" id="PostProcessorCheclistConfirmCollapse">
        <div class="card-body">
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                    <tr>
                        <th style="width:20%;">Section</th>
                        <th style="width:40%;">Checkpoints</th>
                        <th style="width:10%;">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $checkedIds = is_array($reportvalidation?->checklist_id)
                                      ? $reportvalidation->checklist_id
                                      : json_decode($reportvalidation?->checklist_id ?? '[]', true);
                    @endphp

                    @foreach($postchecklistfinal as $section => $data)
                        @php $firstRowForSection = true; @endphp

                        @foreach($data['parents'] as $parentTitle => $items)
                            {{-- Render Parent Heading --}}
                            @if($parentTitle !== '__NO_PARENT__')
                                <tr>
                                    @if($firstRowForSection)
                                        <td rowspan="{{ $data['rowspan'] }}" class="fw-bold align-middle text-center bg-light">
                                            {{ $section }}
                                        </td>
                                        @php $firstRowForSection = false; @endphp
                                    @endif
                                    <td colspan="2" class="fw-bold align-middle text-start ps-3" style="background: #f8f9fa">
                                        <i class="fa fa-folder-open me-2 text-secondary"></i> {{ $parentTitle }}
                                    </td>
                                </tr>
                            @endif

                            {{-- Render Individual Checkpoints --}}
                            @foreach($items as $checklist)
                                <tr>
                                    @if($firstRowForSection)
                                        <td rowspan="{{ $data['rowspan'] }}" class="fw-bold align-middle text-center bg-light">
                                            {{ $section }}
                                        </td>
                                        @php $firstRowForSection = false; @endphp
                                    @endif

                                    <td class="text-start ps-4">{{ $checklist->check_point }}</td>
                                    <td>
                                        <input type="checkbox"
                                               class="form-check-input"
                                               {{ in_array($checklist->id, $checkedIds ?? []) ? 'checked' : '' }}
                                               disabled>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="form-group mb-3">
                    <label class="form-label">Report Link</label>
                    <textarea name="report_link" class="form-control" rows="2"
                              placeholder="Paste the report link here..." disabled> {{$ppReport->report_link }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Post Processor's Notes</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Add any notes about the report..." disabled>{{ $ppReport->notes }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Actual KM</label>
                    <input type="number" class="form-control" name="actual_km" disabled
                        value="{{ $reportvalidation?->actual_km ?? ($check_list->total_km ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Actual Hours</label>
                    <input type="text" class="form-control" name="actual_hours" disabled
                        value="{{ $reportvalidation?->actual_hours ?? ($testResult->working_hours ?? '') }}">
                </div>
               <div class="col-md-6">
                    <h6 class="info-section-title mt-4">Attachments Files</h6>
                     @if(!empty($reportvalidation->docs) && is_array($reportvalidation->docs))
                      <div class="d-flex flex-wrap gap-2">
                        @foreach($reportvalidation->docs as $i => $doc)
                          <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"
                             target="_blank" rel="noopener"
                             class="btn btn-sm btn-outline-primary">
                             Attachment {{ $i + 1 }}
                          </a>
                        @endforeach
                      </div>
                    @else
                      <p class="text-muted">No attachments uploaded.</p>
                    @endif
               </div>
            </div>
        </div>
     </div>
</div>

@endif

{{--@if($request->step == 10)--}}
{{--@php--}}
{{--$reportvalidation = \App\Models\PostProcessorFinalChecklistConfirmation::where('cst_request_id', $request->id)->first();--}}
{{--@endphp--}}
{{--@if($role != 'User')--}}
{{--@if($position == 'Project Manager')--}}
{{--@if($reportvalidation->checklist_confirmation == 'confirmed')--}}

{{--<form action="{{ route('submit.tenth.form') }}" method="POST" enctype="multipart/form-data">--}}
{{--    @csrf--}}
{{--    <input type="hidden" name="cst_request_id" value="{{ $request->id }}">--}}
{{--<div class="card-header">--}}
{{--                <h4><i class="fa fa-spinner   me-2"></i> Post Processor Report Validation</h4>--}}
{{--            </div>--}}
{{--    <div class="card">--}}
{{--        <div class="card-body">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6 mb-3">--}}
{{--                    <label class="form-label">Post Processor's Decision</label>--}}
{{--                    <select class="form-select" name="decision" required>--}}
{{--                        <option value="">-- Select Decision --</option>--}}
{{--                        <option value="accept">Accept</option>--}}
{{--                        <option value="reject">Reject</option>--}}
{{--                        <option value="review">Need Review</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-group mb-3">--}}
{{--                <label class="form-label">Post Processor's Notes</label>--}}
{{--                <textarea class="form-control" name="notes" rows="3"--}}
{{--                    placeholder="Please add your notes if there is any..."></textarea>--}}
{{--            </div>--}}
{{--    <input type="file"--}}
{{--           class="form-control"--}}
{{--           id="docs"--}}
{{--           name="docs[]"--}}
{{--           multiple>--}}
{{--</div>--}}
{{-- <!-- Action Buttons -->--}}
{{--            <div class="d-flex justify-content-end gap-2 mt-3">--}}
{{--                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('cstform') }}'">--}}
{{--                    <i class="fa fa-arrow-left me-1"></i> Cancel--}}
{{--                </button>--}}
{{--                <button type="submit" class="btn btn-primary">--}}
{{--                    <i class="fa fa-save me-1"></i> Submit--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</form>--}}
{{--@endif--}}
{{--@endif--}}
{{--@endif--}}
{{--@endif--}}

{{--@if($request->step >= 11)--}}
{{--@php--}}
{{--$rpvalidation = \App\Models\PostProcessorReportValidation::where('cst_request_id', $request->id)->first();--}}
{{--@endphp--}}

{{--<form action="{{ route('submit.tenth.form') }}" method="POST">--}}
{{--    @csrf--}}
{{--    <input type="hidden" name="cst_request_id" value="{{ $request->id }}">--}}
{{--<div class="card-header" data-bs-toggle="collapse" href="#PostProcessorReportValideCollapse" role="button"--}}
{{--        aria-expanded="false" aria-controls="PostProcessorReportValideollapse">--}}
{{--        <i class="fa fa-spinner me-2"></i> Post Processor Report Validation--}}
{{--    </div>--}}
{{-- <div class="collapse" id="PostProcessorReportValideCollapse">--}}
{{--    <div class="card">--}}
{{--        <div class="card-body">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-6 mb-3">--}}
{{--                    <label class="form-label">Post Processor's Decision</label>--}}
{{--                    @if($rpvalidation)--}}
{{--                    <input type="text" class="form-control"--}}
{{--                        value="{{ ucfirst($rpvalidation->report_validation_decision) }}" disabled>--}}
{{--                    @else--}}
{{--                    <select class="form-select" name="decision" required>--}}
{{--                        <option value="">-- Select Decision --</option>--}}
{{--                        <option value="accept" {{ old('decision') == 'accept' ? 'selected' : '' }}>Accept</option>--}}
{{--                        <option value="reject" {{ old('decision') == 'reject' ? 'selected' : '' }}>Reject</option>--}}
{{--                        <option value="review" {{ old('decision') == 'review' ? 'selected' : '' }}>Need Review</option>--}}
{{--                    </select>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-group mb-3">--}}
{{--                <label class="form-label">Post Processor's Notes</label>--}}
{{--                @if($rpvalidation)--}}
{{--                <textarea class="form-control" rows="3" disabled>{{ $rpvalidation->report_validation_notes }}</textarea>--}}
{{--                @else--}}
{{--                <textarea class="form-control" name="notes" rows="3"--}}
{{--                    placeholder="Please add your notes if there is any...">{{ old('notes') }}</textarea>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--            <div class="col-md-6">--}}
{{--                <h6 class="info-section-title mt-4">Attachments Files</h6>--}}
{{--                @if(!empty($rpvalidation->docs) && is_array($rpvalidation->docs))--}}
{{--                    <div class="d-flex flex-wrap gap-2">--}}
{{--                        @foreach($rpvalidation->docs as $i => $doc)--}}
{{--                              <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"--}}
{{--                                 target="_blank" rel="noopener"--}}
{{--                                 class="btn btn-sm btn-outline-primary">--}}
{{--                                 Attachment {{ $i + 1 }}--}}
{{--                              </a>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    <p class="text-muted">No attachments uploaded.</p>--}}
{{--                @endif</div>--}}
{{--                --}}{{-- Buttons removed completely if readonly --}}
{{--                @if(!$rpvalidation)--}}
{{--                <!-- Action Buttons -->--}}
{{--                <div class="d-flex justify-content-end gap-2 mt-3">--}}
{{--                    <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('cstform') }}'">--}}
{{--                        <i class="fa fa-arrow-left me-1"></i> Cancel--}}
{{--                    </button>--}}
{{--                    <button type="submit" class="btn btn-primary">--}}
{{--                        <i class="fa fa-save me-1"></i> Submit--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                @endif--}}
{{--        </div>--}}
{{--    </div>--}}
{{-- </div>--}}
{{--</form>--}}
{{--@endif--}}


@if($request->step == 11)
@if($role != 'User')
@if($position == 'Project Manager')
<div id="eleventhForm" style=" margin-top:20px;">
     <div class="card-header">
                <h4><i class="fa fa-user-tie  me-2"></i> Team Leader Evaluation</h4>
            </div>
    <form action="{{ route('team.leader.evaluation.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="cst_request_id" value="{{ $request->id }}">

        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Team Leader Evaluation</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Team Leader's Decision</label>
                        <select class="form-select" name="decision" id="teamLeaderDecision" required>
                            <option value="">-- Please Select --</option>
                            <option value="approve" {{ old('decision') == 'approve' ? 'selected' : '' }}>Approve
                            </option>
                            <option value="reject" {{ old('decision') == 'reject' ? 'selected' : '' }}>Reject</option>
                            <option value="review" {{ old('decision') == 'review' ? 'selected' : '' }}>Need Review
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Team Leader's Notes</label>
                    <textarea class="form-control" name="notes" id="teamLeaderNotes" rows="3"
                        placeholder="Please add your notes if there is any...">{{ old('notes') }}</textarea>
                </div>
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
@endif
@endif
@endif

@if($request->step >= 12)
@php
$rpvalidation = \App\Models\TeamLeaderEvaluation::where('cst_request_id', $request->id)->first();
@endphp

<div id="eleventhForm" style="margin-top:20px;">
   <div class="card-header" data-bs-toggle="collapse" href="#TeamLeadECollapse" role="button"
        aria-expanded="false" aria-controls="TeamLeadEollapse">
        <i class="fa fa-user-tie me-2"></i> Team Leader's Decision
    </div>
 <div class="collapse" id="TeamLeadECollapse">
    <form action="{{ route('team.leader.evaluation.submit') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">


                <div class="row">
                    <div class="col-md-6 mb-3">

                        @if($rpvalidation)
                        <input type="text" class="form-control" value="{{ ucfirst($rpvalidation->decision) }}" disabled>
                        @else
                        <select class="form-select" name="decision" id="teamLeaderDecision" required>
                            <option value="">-- Please Select --</option>
                            <option value="approve" {{ old('decision') == 'approve' ? 'selected' : '' }}>Approve
                            </option>
                            <option value="reject" {{ old('decision') == 'reject' ? 'selected' : '' }}>Reject</option>
                            <option value="review" {{ old('decision') == 'review' ? 'selected' : '' }}>Need Review
                            </option>
                        </select>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Team Leader's Notes</label>
                    @if($rpvalidation)
                    <textarea class="form-control" rows="3" disabled>{{ $rpvalidation->notes }}</textarea>
                    @else
                    <textarea class="form-control" name="notes" id="teamLeaderNotes" rows="3"
                        placeholder="Please add your notes if there is any...">{{ old('notes') }}</textarea>
                    @endif
                </div>
                <div class="col-md-6">
                      <h6 class="info-section-title mt-4">Attachments Files</h6>

             @if(!empty($rpvalidation->docs) && is_array($rpvalidation->docs))
  <div class="d-flex flex-wrap gap-2">
    @foreach($rpvalidation->docs as $i => $doc)
      <a href="{{ asset('storage/app/public/docs_files/' . $doc) }}"
         target="_blank" rel="noopener"
         class="btn btn-sm btn-outline-primary">
         Attachment {{ $i + 1 }}
      </a>
    @endforeach
  </div>
@else
  <p class="text-muted">No attachments uploaded.</p>
@endif</div>
            </div>
        </div>
    </form>
</div>
</div>
@endif



@if($request->step == 12)
@php
$rpvalidation = \App\Models\TeamLeaderEvaluation::where('cst_request_id', $request->id)->first();
@endphp
@if($role == 'User')
@if($rpvalidation->decision == 'approve')
<form action="{{ route('cst.final.acceptance.submit') }}" method="POST">
    @csrf
    <input type="hidden" name="cst_request_id" value="{{ $request->id }}">
    <div class="card-header">
        <h4><i class="fa fa-lock  me-2"></i> CST Final Acceptance</h4>
    </div>
    <div id="twelfthForm" style="margin-top:20px;">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CST's Decision</label>
                        <select class="form-select" name="decision" required>
                            <option value="">-- Select Decision --</option>
                            <option value="accept">Accept</option>
                            <option value="reject">Reject</option>
                            <option value="review">Need Review</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">CST's Notes</label>
                    <textarea class="form-control" name="notes" rows="3"
                        placeholder="Please add your notes if there is any..."></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('cstform') }}'">
                        <i class="fa fa-arrow-left me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Submit Final Acceptance
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>
@endif
@endif
@endif

@if($request->step >= 13)
@php
$acceptance = \App\Models\CstFinalAcceptance::where('cst_request_id', $request->id)->first();
@endphp

<div id="twelfthForm" style="margin-top:20px;">
    <div class="card-header" data-bs-toggle="collapse" href="#CSTFinalCollapse" role="button"
        aria-expanded="false" aria-controls="CSTFinalollapse">
        <i class="fa fa-lock me-2"></i> CST Final Acceptance
    </div>
 <div class="collapse" id="CSTFinalCollapse">
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3"></h5>

            @if($acceptance)
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">CST's Decision</label>
                    <input type="text" class="form-control" value="{{ ucfirst($acceptance->decision) }}" disabled>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">CST's Notes</label>
                <textarea class="form-control" rows="3" disabled>{{ $acceptance->notes }}</textarea>
            </div>
            @endif

        </div>
    </div>
 </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Start time from DB as ISO string (or null)
        const startIso = @json(optional(
        \App\Models\FieldTestResult::where('cst_request_id', $cstid)->first())->start_time);

        // If no start time from DB, bail
        if (!startIso) {
            document.getElementById('testDuration').value = "";
            return;
        }

        const start = new Date(startIso);

        // End time from hidden field (fallback to now if empty)
        const endField = document.getElementById('testEnd');
        let end = endField && endField.value ? new Date(endField.value) : new Date();

        if (!isNaN(start.getTime()) && !isNaN(end.getTime()) && end > start) {
            const diffMs = end - start;                 // milliseconds
            const hours  = (diffMs / 3600000).toFixed(2); // hours with 2 decimals
            document.getElementById('testDuration').value = hours;
        } else {
            document.getElementById('testDuration').value = "";
        }
    });
</script>
<script>
    (function () {
        const ending = document.getElementById('endingKm');
        const total  = document.getElementById('totalKm');

        @php
            $startKm = optional(
              \App\Models\SelectedChecklist::where('cst_request_id', $cstid)->first()
            )->starting_km;
        @endphp
        const startKm = {{ (float)($startKm ?? 0) }};

        function updateTotal() {
            const endKm = parseFloat(ending.value || '0');
            if (!isNaN(endKm) && !isNaN(startKm) && endKm >= startKm) {
                total.value = (endKm - startKm).toFixed(2) + ' KM';
            } else {
                total.value = '';
            }
        }

        ending?.addEventListener('input', updateTotal);
    })();
</script>
