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
        color: #e3e3e3;
         font-size: 14px;
        
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
    ToDo List <small class="text-muted"></small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-list-alt me-2"></i> ToDo List</h4>
        
    </div>

    <div class="card-body">
        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Assigned By</th>
                    <th>Requested Test</th>
                    <th>Test Type</th>
                    <th>Status</th>
                    <th>Process</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $data)
                @php
                    $statusMap = [
                        '0' => ['label' => 'Pending', 'class' => 'warning'],
                        '1' => ['label' => 'Approved', 'class' => 'success'],
                        '2' => ['label' => 'Rejected', 'class' => 'danger'],
                        '3' => ['label' => 'Ongoing', 'class' => 'info'],
                        '4' => ['label' => 'Completed', 'class' => 'primary'],
                    ];
                    $status = $statusMap[$data->status] ?? ['label' => 'Unknown', 'class' => 'secondary'];
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->user->name }}</td>
                    <td>{{ $data->cstRequest->request_type }}</td>    
                    <td>{{ $data->cstRequest->test_type }}</td>    
                    <td><span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span></td>
                    <td>
                        @if($data->assign_to == Auth::id() && $data->status == 1)
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm" onclick="approveRequest({{ $data->id }})">
                                    <i class="fa fa-check me-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="rejectRequest({{ $data->id }})">
                                    <i class="fa fa-times me-1"></i> Reject
                                </button>
                            </div>
                        @else
                            @php
                                $disable =  in_array($data->status, [1,2,3,54]);
                            @endphp
                            <a href="{{ route('tester.checklists', ['id' => $data->cstRequest->id]) }}"
                               class="btn btn-info btn-sm ">
                                <i class="fa fa-cogs me-1"></i> Process
                            </a>
                            <a href="{{ route('cstform.view', $data->cstRequest->id) }}" target="_blank">
                            <i class="fa fa-external-link-alt text-info"></i> Report
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
