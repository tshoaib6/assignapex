@extends('layout.default')

@section('title', 'Post Processor Rejection')

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
    Post Processor Rejection <small class="text-muted">Manage post-processor rejection issues</small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-exclamation-triangle me-2 text-danger"></i> Rejection List</h4>
        <a href="{{ route('post_processor_rejections.create') }}" class="btn btn-primary">
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
                    <th>Field Issue</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejections as $rejection)
                <tr>
                    <td>{{ $rejection->id }}</td>
                    <td>{{ $rejection->field }}</td>
                    <td>
                        <a href="{{ route('post_processor_rejections.edit', $rejection->id) }}">
                            <i class="fa fa-edit text-success" title="Edit"></i>
                        </a>
                        <form action="{{ route('post_processor_rejections.destroy', $rejection->id) }}" 
                              method="POST" 
                              style="display:inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this issue?')">
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
                    <td colspan="3" class="text-center text-muted">No rejection issues found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
