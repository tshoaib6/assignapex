@extends('layout.default')

@section('title', 'Post Processor Check List')

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

    .status-badge {
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        color: white;
    }

    .status-yes { background: #28a745; }   /* Green */
    .status-no { background: #dc3545; }    /* Red */
    .status-na { background: #6c757d; }    /* Grey */

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
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Post Processor Check List <small class="text-muted">Manage your post-processor checkpoints</small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-tasks me-2"></i> Checklist List</h4>
        <a href="{{ route('post-processor-checklists.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add New
        </a>
    </div>

    <div class="card-body">
        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Section</th>
                    <th>Parent Title</th>
                    <th>Check Point</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($checklists as $checklist)
                <tr>
                    <td>{{ $checklist->id }}</td>
                    <td>{{ $checklist->section }}</td>
                    <td>{{ $checklist->parent_title }}</td>
                    <td>{{ $checklist->check_point }}</td>
                    <td>
                        <span class="status-badge 
                            {{ $checklist->status == 'Yes' ? 'status-yes' : ($checklist->status == 'No' ? 'status-no' : 'status-na') }}">
                            {{ $checklist->status }}
                        </span>
                    </td>
                    <td>{{ $checklist->remarks }}</td>
                    <td>
                        <a href="{{ route('post-processor-checklists.edit', $checklist->id) }}">
                            <i class="fa fa-edit text-success" title="Edit"></i>
                        </a>
                        <form action="{{ route('post-processor-checklists.destroy', $checklist->id) }}" method="POST" style="display:inline-block"
                              onsubmit="return confirm('Are you sure you want to delete this record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; padding:0;">
                                <i class="fa fa-trash text-danger ms-2" title="Delete"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
