@extends('layout.default')

@section('title', 'ToDo List')

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

.btn-info {
    background: linear-gradient(45deg, #4e73df, #4e73df);
    border: none;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 6px;
    color: #fff;
}

.btn-info:hover {
    background: linear-gradient(45deg, #4e73df, #4e73df);
    color: #fff;

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

.badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
}
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    ReDO List <small class="text-muted"></small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-list-alt me-2"></i>ReDo Tasks</h4>
    </div>

    <div class="card-body">
        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Requested By</th>
                    <th>Step</th>
                    <th>Status</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    @php
                        $request = $task->cstRequest;
                        $user = $request->user ?? null;
                    @endphp
                    <tr>
                        <td>{{ $request->unique_request_id ?? '-' }}</td>
                        <td>{{ $user->name ?? 'N/A' }}</td>
                        <td>
                            @if($task instanceof \App\Models\PPdataValidation)
                            <span class="badge bg-primary">📝 Data Validation</span>
                            @elseif($task instanceof \App\Models\PostProcessorFinalChecklistConfirmation)
                            <span class="badge bg-warning text-dark">✅ Final Checklist</span>
                            @elseif($task instanceof \App\Models\PostProcessorReportValidation)
                            <span class="badge bg-success">📊 Report Validation</span>
                            @elseif($task instanceof \App\Models\TeamLeaderEvaluation)
                                <span class="badge" style="background-color: #6f42c1; color: white;">🧑‍💼 Team Lead
                                Evaluation</span>
                            @elseif($task instanceof \App\Models\SelectedChecklist)
                                <span class="badge" style="background-color: #6f42c1; color: white;">🧑‍💼 Post Processor
                                Evaluation</span>
                            @elseif($task instanceof \App\Models\CstFinalAcceptance)
                            <span class="badge" style="background-color: #6f42c1; color: white;">
                                ✅ Final Acceptance
                            </span>
                            @endif
                        </td>

                        <td>
                            @if($task instanceof \App\Models\PPdataValidation)
                            <span class="badge bg-danger">{{$task->decision}}</span>
                            @elseif($task instanceof \App\Models\PostProcessorFinalChecklistConfirmation)
                            <span class="badge bg-danger">{{$task->checklist_confirmation}}</span>
                            @elseif($task instanceof \App\Models\PostProcessorReportValidation)
                            <span class="badge bg-danger">{{$task->report_validation_decision}}</span>
                            @elseif($task instanceof \App\Models\TeamLeaderEvaluation)
                            <span class="badge bg-danger">{{$task->decision}}</span>
                            @elseif($task instanceof \App\Models\CstFinalAcceptance)
                                <span class="badge bg-danger">{{$task->decision}}</span>
                            @elseif($task instanceof \App\Models\SelectedChecklist)
                                <span class="badge bg-danger">Sent Back</span>
                            @endif
                        </td>
                        <td>
                            @if($task instanceof \App\Models\PostProcessorReportValidation)
                                {{$task->report_validation_notes}}
                            @elseif($task instanceof \App\Models\SelectedChecklist)
                                Sent Back by Post Processor
                            @else
                                {{$task->notes}}
                            @endif
                        </td>
                        <td>
                            <!-- Optional: action icons like report or delete -->
                            @if($task instanceof \App\Models\PPdataValidation)
                            <a href="{{ route('redo.ppvalidation', $request->id ) }}">
                                <i class="fa fa-external-link-alt text-info"></i> Redo
                            </a>
                            @elseif($task instanceof \App\Models\PostProcessorFinalChecklistConfirmation)
                            <a href="{{ route('pp.checklist.redo', $request->id) }}">
                                <i class="fa fa-external-link-alt text-info"></i> Redo
                            </a>
                            @elseif($task instanceof \App\Models\PostProcessorReportValidation)
                            <a href="{{ route('pp.report.redo', $request->id) }}">
                                <i class="fa fa-external-link-alt text-info"></i> Redo
                            </a>
                            @elseif($task instanceof \App\Models\TeamLeaderEvaluation)
                            <a href="{{ route('evaluation.teamleader.redo', $request->id) }}">
                                <i class="fa fa-external-link-alt text-info"></i> Redo
                            </a>
                            @elseif($task instanceof \App\Models\CstFinalAcceptance)
                            <a href="{{ route('final.acceptance.redo', $request->id) }}">
                                <i class="fa fa-external-link-alt text-info"></i> Redo
                            </a>
                            @elseif($task instanceof \App\Models\SelectedChecklist)
                                <a href="{{ route('tester.checklists', ['id' => $request->id]) }}">
                                    <i class="fa fa-external-link-alt text-info"></i> Redo
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
