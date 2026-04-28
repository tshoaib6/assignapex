@extends('layout.default')

@section('title', 'Request List')

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
    Request List <small class="text-muted">Manage all generated requests</small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-list-alt me-2"></i> All Requests</h4>
        @if($role == 'User' || $position === 'Project Manager')
            <a href="{{ route('cstform.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Generate Request
            </a>
        @endif
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
                    <th>Status</th>
                    <th>Process</th>
                    <th>Request Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                {{-- USER SECTION --}}
                @if($role == 'User')
                    @foreach($CSTForm as $data)
                        @php
                            $statusMap = [
                                '1' => ['label' => 'Pending', 'class' => 'warning'],
                                '2' => ['label' => 'Approved', 'class' => 'success'],
                                '3' => ['label' => 'Rejected', 'class' => 'danger'],
                                '4' => ['label' => 'Ongoing', 'class' => 'info'],
                                '5' => ['label' => 'Completed', 'class' => 'primary'],
                            ];
                            $step = $data->step;
                            $statusValue = $data->status;
                            $status = ($step == 12 && $statusValue == 4)
                                ? ['label' => 'Pending', 'class' => 'warning']
                                : ($statusMap[$statusValue] ?? ['label' => 'Unknown', 'class' => 'secondary']);
                        @endphp
                        <tr>
                            <td>{{ $data->unique_request_id ?? 'N/A' }}</td>
                            <td>{{ $data->created_at->toDayDateTimeString() ?? 'N/A'}}</td>
                            <td>{{ $data->user->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span></td>
                            <td>
                                @if($data->assign_to == Auth::id() && $data->status == 1)
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm"
                                                onclick="approveRequest({{ $data->id }})">
                                            <i class="fa fa-check me-1"></i> Approve
                                        </button>
{{--                                        <button type="button" class="btn btn-warning btn-sm"--}}
{{--                                                onclick="rejectRequest({{ $data->id }})">--}}
{{--                                            <i class="fa fa-check me-1"></i> Send Back--}}
{{--                                        </button>--}}
                                    </div>
                                @endif
                                <a href="{{ route('assign.tester', ['id' => $data->id]) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-cogs me-1"></i> Process
                                </a>
                            </td>
                            <td>{{ $data->created_at->toDayDateTimeString() ?? 'N/A'}}</td>
                            <td>
                                @if(Auth::id() == ($data->user->id ?? null) && $data->status == 1)
                                    <form action="{{ route('cstrequest.destroy', $data->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 border-0 bg-transparent ms-2">
                                            <i class="fa fa-trash text-danger" title="Delete"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('cstform.view', $data->id) }}" target="_blank" class="ms-2">
                                    <i class="fa fa-external-link-alt text-info"></i> Report
                                </a>
                            </td>
                        </tr>
                    @endforeach

                {{-- PROJECT MANAGER SECTION --}}
                @elseif($position == 'Project Manager')
                    @foreach($CSTForm as $data)
                        @php
                            $statusMap = [
                                '1' => ['label' => 'Pending', 'class' => 'warning'],
                                '2' => ['label' => 'Approved', 'class' => 'success'],
                                '3' => ['label' => 'Rejected', 'class' => 'danger'],
                                '4' => ['label' => 'Ongoing', 'class' => 'info'],
                                '5' => ['label' => 'Completed', 'class' => 'primary'],
                            ];
                            $step = $data->step;
                            $statusValue = $data->status;
                            $status = ($step == 1 && $statusValue == 1)
                                ? ['label' => 'Ongoing', 'class' => 'info']
                                : ($statusMap[$statusValue] ?? ['label' => 'Unknown', 'class' => 'secondary']);
                        @endphp
                        <tr>
                            <td>{{ $data->unique_request_id ?? 'N/A' }}</td>
                            <td>{{ $data->user->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span></td>
                            <td>
                                @if($data->assign_to == Auth::id() && $data->status == 1)
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm"
                                            onclick="approveRequest({{ $data->id }})">
                                            <i class="fa fa-check me-1"></i> Approve
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('assign.tester', ['id' => $data->id]) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-cogs me-1"></i> Process
                                    </a>
                                @endif
                            </td>
                            <td>{{ $data->created_at->toDayDateTimeString() ?? 'N/A'}}</td>
                            <td>
                                @if(Auth::id() == ($data->user->id ?? null) && $data->status == 1)
                                    <form action="{{ route('cstrequest.destroy', $data->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 border-0 bg-transparent ms-2">
                                            <i class="fa fa-trash text-danger" title="Delete"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('cstform.view', $data->id) }}" target="_blank" class="ms-2">
                                    <i class="fa fa-external-link-alt text-info"></i> Report
                                </a>
                            </td>
                        </tr>
                    @endforeach

                {{-- TEAM LEAD / OTHER ROLES --}}
                @else
                    @foreach($checklists as $checklist)
                        @php
                            $statusMap = [
                                '0' => ['label' => 'Pending', 'class' => 'warning'],
                                '1' => ['label' => 'Approved', 'class' => 'success'],
                                '2' => ['label' => 'Rejected', 'class' => 'danger'],
                                '3' => ['label' => 'Ongoing', 'class' => 'info'],
                                '4' => ['label' => 'Completed', 'class' => 'primary'],
                                '5' => ['label' => 'Completed', 'class' => 'primary'],
                            ];
                            $status = $statusMap[$checklist->cstRequest->status] ?? ['label' => 'Unknown', 'class' => 'secondary'];
                        @endphp
                        <tr>
                            <td>{{ $checklist->cstRequest->unique_request_id ?? 'N/A' }}</td>
                            <td>{{ $checklist->cstRequest->user->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span></td>
                            <td>
                                @if($position === 'Team Lead')
                                    <a href="{{ route('assign.tester', ['id' => $checklist->cstRequest->id]) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-cogs me-1"></i> Process
                                    </a>
                                @else
                                    <a href="{{ route('assign.tester', ['id' => $checklist->cstRequest->id]) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-cogs me-1"></i> Process
                                    </a>
                                @endif
                            </td>
                            <td>{{ $checklist->cstRequest->created_at->toDayDateTimeString() ?? 'N/A'}}</td>
                            <td>
                                <a href="{{ route('cstform.view', $checklist->cstRequest->id) }}" target="_blank">
                                    <i class="fa fa-external-link-alt text-info"></i> Report
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>
    </div>
</div>

@endsection

@push('js')
<script>
function approveRequest(id) {
    if (confirm("Are you sure you want to approve this request?")) {
        fetch(`/cst-request/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => location.reload());
    }
}

function rejectRequest(id) {
    if (confirm("Are you sure you want to reject this request?")) {
        fetch(`/cst-request/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => location.reload());
    }
}
</script>
@endpush
