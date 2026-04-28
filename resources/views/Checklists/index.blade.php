@extends('layout.default')

@section('title', 'Checklists')

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

    .section-title {
        background: #1155cc;
        color: #fff;
        padding: 6px 12px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 4px;
        margin-top: 20px;
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

    img.checklist-img {
        width: 50px;
        height: 50px;
        border-radius: 5px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Checklists <small class="text-muted">Manage and review all checklists</small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-clipboard-list me-2"></i> All Checklists</h4>
        <a href="{{ route('checklists.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add Checklist
        </a>
    </div>

    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @foreach($checklists as $section => $items)
            <div class="section-title">{{ $section }}</div>
            <table class="table table-bordered table-striped mb-4">
                <thead>
                    <tr>
                        <th>Check Point</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->check_point }}</td>
                            <td>
                                @if($item->status == 'Approved')
                                    <span class="badge bg-success">{{ $item->status }}</span>
                                @elseif($item->status == 'Pending')
                                    <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                @elseif($item->status == 'Rejected')
                                    <span class="badge bg-danger">{{ $item->status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td>{{ $item->remarks }}</td>
                            <td>
                                @if($item->image)
                                    <img src="{{ asset('storage/'.$item->image) }}" class="checklist-img">
                                @else
                                    <i class="fas fa-image text-muted" style="font-size:18px;"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('checklists.edit', $item->id) }}">
                                    <i class="fa fa-edit text-success" title="Edit"></i>
                                </a>
                                <a href="{{ route('checklists.destroy', $item->id) }}"
                                   onclick="return confirm('Delete this record?')" class="ms-2">
                                    <i class="fa fa-trash text-danger" title="Delete"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</div>

@endsection
