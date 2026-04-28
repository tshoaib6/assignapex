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
    </div>
@endif
@if($request->step >= 3)
<div class="card">
    <div class="card-header" data-bs-toggle="collapse" href="#testerAssignmentCollapse" role="button"
        aria-expanded="false" aria-controls="testerAssignmentCollapse">
        <i class="fa fa-user-check"></i> Assigned Tester
    </div>
</div>
@endif
@if($request->step >=4)
<div class="card mb-4">
    <div class="card-header" data-bs-toggle="collapse" href="#drivertestCollapse" role="button"
        aria-expanded="false" aria-controls="drivertestCollapse">
        <i class="fa fa-clipboard-check me-2"></i> Driver Checklist Summary
    </div>
</div>
@endif

<!-- Test Result -->


@php

    $testResult = \App\Models\FieldTestResult::where('cst_request_id', $cstid)->first();
    $user = Auth::user();
    $role = $user->getRoleNames()->first();

    if ($testResult->checklist_id) {
        $postchecklistIds = json_decode($testResult->checklist_id, true);
        if (is_array($postchecklistIds)) {
            $postchecklistItems = \App\Models\PostProcessorChecklist::whereIn('id', $postchecklistIds)
                                    ->orderBy('section')
                                    ->orderBy('parent_title')
                                    ->orderBy('id')
                                    ->get();
        }

        $postchecklistItem = $postchecklistItems->groupBy('section')->map(function ($items) {
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
    }
@endphp
{{-- ===================== FIELD TEST RESULT ===================== --}}
@if($request->step >=5)
    <div class="card ">
        <div class="card-header" data-bs-toggle="collapse" href="#fieldtestCollapse" role="button"
            aria-expanded="false" aria-controls="fieldtestCollapse">
            <i class="fa fa-binoculars me-2"></i> Field Test Result
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
</div>
@endif

@php
$user = Auth::user();
$position = optional($user->teamDetail)->position;
@endphp

@if($request->step == 8)
    @php
        $datavalidation = \App\Models\PPdataValidation::where('cst_request_id', $request->id)->first();
    @endphp

    @if($position == 'Post Processor' && $datavalidation && $datavalidation->decision == 'accept')
        <form action="{{ route('postprocessor.storereport') }}" method="POST" id="eighthForm" style="margin-top:20px;" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                <h4><i class="fa fa-file-excel  me-2"></i> Generate Report</h4>
            </div>
                <div class="card-body">


                    <input type="hidden" name="cst_request_id" value="{{ $request->id }}">

                    <div class="form-group mb-3">
                        <label class="form-label">Report Link</label>
                        <textarea class="form-control" name="report_link" rows="2" placeholder="Paste the report link here..." required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Post Processor's Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Add any notes about the report..."></textarea>
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
    @endif
@endif

@if($request->step >= 9)
@php
$reportvalidation = \App\Models\PostProcessorReport::where('cst_request_id', $request->id)->first();
@endphp
<div class="card" style="margin-top: 20px">
    <div class="card-header" data-bs-toggle="collapse" href="#PostProcessorDataVCollapse" role="button"
        aria-expanded="false" aria-controls="PostProcessorDataVollapse">
        <i class="fa fa-file-excel me-2"></i> Generate Report
    </div>
</div>
@endif

@if($request->step == 9)
@if($position == 'Team Lead')
<div id="ninthForm" style="margin-top:20px;">

    <div class="card">
        <div class="card-header">
                <h4><i class="fa fa-check  me-2"></i> Report Analysis</h4>
            </div>
        <div class="card-body">
            <form id="ninthFormData" action="{{ route('submit.ninth.form') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="cst_request_id" value="{{ $request->id }}">

                @csrf
                <p>I read all the items in the <strong>"Post Processor Final Checklist"</strong></p>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Checklist Confirmation</label>
                        <select class="form-select" name="checklist_confirmation" required>
                            <option value="">-- Select --</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="not_confirmed">Not Confirmed</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Actual KM</label>
                        <input type="number" class="form-control" name="actual_km" placeholder="Enter Actual KM" value="{{ $check_list->total_km ?? '' }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Actual Hours</label>
                        <input type="text" class="form-control" name="actual_hours" placeholder="Enter Actual Hours" value="{{ $testResult->working_hours }}">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="docs" class="form-label">Attachments (optional)</label>
                    <input type="file" class="form-control" id="docs" name="docs[]" multiple />
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('cstform') }}'">
                        <i class="fa fa-arrow-left me-1"></i> Cancel
                    </button>
                    <button type="button" onclick="submitNinthForm()" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function submitNinthForm() {
    document.getElementById('ninthFormData').submit();
}
</script>

@endif
@endif

@if($request->step >= 10)
@php
$reportvalidation = \App\Models\PostProcessorFinalChecklistConfirmation::where('cst_request_id', $request->id)->first();
@endphp
<div class="card shadow-sm rounded mb-4">
       <div class="card-header" data-bs-toggle="collapse" href="#PostProcessorCheclistConfirmCollapse" role="button"
        aria-expanded="false" aria-controls="PostProcessorCheclistConfirmollapse">
        <i class="fa fa-check me-2"></i> Report Analysis
    </div>
</div>

@endif

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

        // Fetch starting_km from server (selected_checklists) to compute totals client-side
        // You already have $cstid here:
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
